/**
 * ========================================
 * COOKIE CONSENT MANAGEMENT SYSTEM
 * Version: 1.0
 * Description: GDPR/CCPA compliant cookie consent banner
 * ========================================
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        cookieName: 'cookie_consent',
        cookieExpiry: 365, // days
        checkInterval: 100, // ms - check if banner should show
    };

    // Cookie Consent Manager
    const CookieConsent = {

        /**
         * Initialize the cookie consent system
         */
        init: function() {
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.setup());
            } else {
                this.setup();
            }
        },

        /**
         * Setup the cookie consent system
         */
        setup: function() {
            // Check if user has already consented
            const consent = this.getConsent();

            if (!consent) {
                // Show banner after a short delay
                setTimeout(() => this.showBanner(), 500);
            } else {
                // Apply saved preferences
                this.applyConsent(consent);
            }

            // Setup event listeners
            this.setupEventListeners();
        },

        /**
         * Setup all event listeners
         */
        setupEventListeners: function() {
            // Accept All button
            const acceptAllBtn = document.getElementById('cookie-accept-all');
            if (acceptAllBtn) {
                acceptAllBtn.addEventListener('click', () => this.acceptAll());
            }

            // Reject All button
            const rejectAllBtn = document.getElementById('cookie-reject-all');
            if (rejectAllBtn) {
                rejectAllBtn.addEventListener('click', () => this.rejectAll());
            }

            // Customize button
            const customizeBtn = document.getElementById('cookie-customize');
            if (customizeBtn) {
                customizeBtn.addEventListener('click', () => this.showSettings());
            }

            // Save preferences button
            const saveBtn = document.getElementById('cookie-save-preferences');
            if (saveBtn) {
                saveBtn.addEventListener('click', () => this.savePreferences());
            }

            // Close modal button
            const closeBtn = document.querySelector('.cookie-settings-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.hideSettings());
            }

            // Click outside modal to close
            const modal = document.getElementById('cookie-settings-modal');
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.hideSettings();
                    }
                });
            }
        },

        /**
         * Show the cookie consent banner
         */
        showBanner: function() {
            const banner = document.getElementById('cookie-consent-banner');
            if (banner) {
                banner.classList.add('show');
            }
        },

        /**
         * Hide the cookie consent banner
         */
        hideBanner: function() {
            const banner = document.getElementById('cookie-consent-banner');
            if (banner) {
                banner.classList.remove('show');
                // Remove from DOM after animation
                setTimeout(() => {
                    banner.style.display = 'none';
                }, 400);
            }
        },

        /**
         * Show the cookie settings modal
         */
        showSettings: function() {
            const modal = document.getElementById('cookie-settings-modal');
            if (modal) {
                // Load current preferences
                const consent = this.getConsent() || {
                    essential: true,
                    functional: false,
                    analytics: false,
                    marketing: false
                };

                // Set toggle states
                document.getElementById('cookie-essential').checked = true; // Always true
                document.getElementById('cookie-functional').checked = consent.functional;
                document.getElementById('cookie-analytics').checked = consent.analytics;
                document.getElementById('cookie-marketing').checked = consent.marketing;

                modal.classList.add('show');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
        },

        /**
         * Hide the cookie settings modal
         */
        hideSettings: function() {
            const modal = document.getElementById('cookie-settings-modal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = ''; // Restore scrolling
            }
        },

        /**
         * Accept all cookies
         */
        acceptAll: function() {
            const consent = {
                essential: true,
                functional: true,
                analytics: true,
                marketing: true,
                timestamp: new Date().toISOString()
            };

            this.saveConsent(consent);
            this.hideBanner();
            this.applyConsent(consent);
            this.sendConsentToServer(consent);
        },

        /**
         * Reject all non-essential cookies
         */
        rejectAll: function() {
            const consent = {
                essential: true,
                functional: false,
                analytics: false,
                marketing: false,
                timestamp: new Date().toISOString()
            };

            this.saveConsent(consent);
            this.hideBanner();
            this.applyConsent(consent);
            this.sendConsentToServer(consent);
        },

        /**
         * Save custom preferences from settings modal
         */
        savePreferences: function() {
            const consent = {
                essential: true, // Always true
                functional: document.getElementById('cookie-functional').checked,
                analytics: document.getElementById('cookie-analytics').checked,
                marketing: document.getElementById('cookie-marketing').checked,
                timestamp: new Date().toISOString()
            };

            this.saveConsent(consent);
            this.hideSettings();
            this.hideBanner();
            this.applyConsent(consent);
            this.sendConsentToServer(consent);
        },

        /**
         * Save consent to cookie
         */
        saveConsent: function(consent) {
            const consentString = JSON.stringify(consent);
            const expiryDate = new Date();
            expiryDate.setDate(expiryDate.getDate() + CONFIG.cookieExpiry);

            document.cookie = `${CONFIG.cookieName}=${encodeURIComponent(consentString)}; expires=${expiryDate.toUTCString()}; path=/; SameSite=Lax`;
        },

        /**
         * Get consent from cookie
         */
        getConsent: function() {
            const name = CONFIG.cookieName + '=';
            const decodedCookie = decodeURIComponent(document.cookie);
            const cookieArray = decodedCookie.split(';');

            for (let i = 0; i < cookieArray.length; i++) {
                let cookie = cookieArray[i].trim();
                if (cookie.indexOf(name) === 0) {
                    try {
                        return JSON.parse(cookie.substring(name.length));
                    } catch (e) {
                        console.error('Error parsing consent cookie:', e);
                        return null;
                    }
                }
            }
            return null;
        },

        /**
         * Apply consent preferences (enable/disable features)
         */
        applyConsent: function(consent) {
            // Essential cookies - always enabled
            // (Session cookies are already running)

            // Functional cookies (Google Maps, preferences, etc.)
            if (consent.functional) {
                this.enableFunctionalCookies();
            } else {
                this.disableFunctionalCookies();
            }

            // Analytics cookies
            if (consent.analytics) {
                this.enableAnalyticsCookies();
            } else {
                this.disableAnalyticsCookies();
            }

            // Marketing cookies
            if (consent.marketing) {
                this.enableMarketingCookies();
            } else {
                this.disableMarketingCookies();
            }

            // Store in sessionStorage for quick access
            sessionStorage.setItem('cookie_consent', JSON.stringify(consent));
        },

        /**
         * Enable functional cookies
         */
        enableFunctionalCookies: function() {
            // Show Google Maps if hidden
            const mapsPlaceholder = document.querySelector('.google-maps-placeholder');
            const mapsIframe = document.querySelector('.google-maps-iframe');

            if (mapsPlaceholder && mapsIframe) {
                mapsPlaceholder.style.display = 'none';
                mapsIframe.style.display = 'block';
            }

            console.log('Functional cookies enabled');
        },

        /**
         * Disable functional cookies
         */
        disableFunctionalCookies: function() {
            // Hide Google Maps
            const mapsPlaceholder = document.querySelector('.google-maps-placeholder');
            const mapsIframe = document.querySelector('.google-maps-iframe');

            if (mapsPlaceholder && mapsIframe) {
                mapsPlaceholder.style.display = 'block';
                mapsIframe.style.display = 'none';
            }

            console.log('Functional cookies disabled');
        },

        /**
         * Enable analytics cookies
         */
        enableAnalyticsCookies: function() {
            // Placeholder for Google Analytics or other analytics
            // Example: Load Google Analytics script
            // window.dataLayer = window.dataLayer || [];
            // function gtag(){dataLayer.push(arguments);}
            // gtag('js', new Date());
            // gtag('config', 'GA_MEASUREMENT_ID');

            console.log('Analytics cookies enabled (not configured yet)');
        },

        /**
         * Disable analytics cookies
         */
        disableAnalyticsCookies: function() {
            // Disable analytics tracking
            // window['ga-disable-GA_MEASUREMENT_ID'] = true;

            console.log('Analytics cookies disabled');
        },

        /**
         * Enable marketing cookies
         */
        enableMarketingCookies: function() {
            // Placeholder for Facebook Pixel or other marketing pixels
            // Example: Load Facebook Pixel
            // !function(f,b,e,v,n,t,s){...}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
            // fbq('init', 'YOUR_PIXEL_ID');
            // fbq('track', 'PageView');

            console.log('Marketing cookies enabled (not configured yet)');
        },

        /**
         * Disable marketing cookies
         */
        disableMarketingCookies: function() {
            // Disable marketing tracking
            console.log('Marketing cookies disabled');
        },

        /**
         * Send consent to server for logging
         */
        sendConsentToServer: function(consent) {
            // Use SITE_CONFIG from footer.php or fallback to window.location.origin
            const siteUrl = (typeof SITE_CONFIG !== 'undefined' && SITE_CONFIG.siteUrl)
                ? SITE_CONFIG.siteUrl
                : window.location.origin;

            // Send consent preferences to server
            fetch(`${siteUrl}/includes/save-cookie-consent.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(consent)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Cookie consent saved to server');
                } else {
                    console.error('Failed to save consent:', data.message);
                }
            })
            .catch(error => {
                console.error('Error saving consent:', error);
            });
        },

        /**
         * Check if specific consent is granted
         */
        hasConsent: function(type) {
            const consent = this.getConsent();
            if (!consent) return false;
            return consent[type] === true;
        }
    };

    // Public API
    window.CookieConsent = {
        hasConsent: function(type) {
            return CookieConsent.hasConsent(type);
        },
        getConsent: function() {
            return CookieConsent.getConsent();
        },
        openSettings: function() {
            CookieConsent.showSettings();
        },
        acceptAll: function() {
            CookieConsent.acceptAll();
        },
        rejectAll: function() {
            CookieConsent.rejectAll();
        },
        // Reset consent and show banner again (for testing)
        reset: function() {
            document.cookie = 'cookie_consent=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax';
            sessionStorage.removeItem('cookie_consent');
            CookieConsent.showBanner();
            console.log('Cookie consent reset - banner shown again');
        }
    };

    // Initialize
    CookieConsent.init();

})();
