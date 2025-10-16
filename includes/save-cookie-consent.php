<?php
/**
 * ========================================
 * SAVE COOKIE CONSENT TO DATABASE
 * Version: 1.0
 * Description: Save user cookie consent preferences
 * ========================================
 */

require_once 'config.php';

// Set JSON response header
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data || !isset($data['essential'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input data'
    ]);
    exit;
}

// Extract consent data
$consentEssential = isset($data['essential']) ? (int)$data['essential'] : 1;
$consentFunctional = isset($data['functional']) ? (int)$data['functional'] : 0;
$consentAnalytics = isset($data['analytics']) ? (int)$data['analytics'] : 0;
$consentMarketing = isset($data['marketing']) ? (int)$data['marketing'] : 0;

// Get user info
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$sessionId = session_id();
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

// Sanitize user agent (max 1000 chars)
$userAgent = substr($userAgent, 0, 1000);

try {
    // Check if consent already exists for this user/session
    $checkSql = "SELECT id FROM cookie_consents WHERE ";
    if ($userId) {
        $checkSql .= "user_id = :user_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute(['user_id' => $userId]);
    } else {
        $checkSql .= "session_id = :session_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute(['session_id' => $sessionId]);
    }

    $existingConsent = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingConsent) {
        // Update existing consent
        $updateSql = "UPDATE cookie_consents SET
                        consent_essential = :essential,
                        consent_functional = :functional,
                        consent_analytics = :analytics,
                        consent_marketing = :marketing,
                        ip_address = :ip,
                        user_agent = :user_agent,
                        updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";

        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            'essential' => $consentEssential,
            'functional' => $consentFunctional,
            'analytics' => $consentAnalytics,
            'marketing' => $consentMarketing,
            'ip' => $ipAddress,
            'user_agent' => $userAgent,
            'id' => $existingConsent['id']
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Cookie consent updated successfully',
            'action' => 'updated'
        ]);

    } else {
        // Insert new consent
        $insertSql = "INSERT INTO cookie_consents
                        (user_id, session_id, consent_essential, consent_functional,
                         consent_analytics, consent_marketing, ip_address, user_agent)
                      VALUES
                        (:user_id, :session_id, :essential, :functional,
                         :analytics, :marketing, :ip, :user_agent)";

        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'essential' => $consentEssential,
            'functional' => $consentFunctional,
            'analytics' => $consentAnalytics,
            'marketing' => $consentMarketing,
            'ip' => $ipAddress,
            'user_agent' => $userAgent
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Cookie consent saved successfully',
            'action' => 'created'
        ]);
    }

} catch (PDOException $e) {
    // Log error
    error_log("Cookie consent save error: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save cookie consent'
    ]);
}
