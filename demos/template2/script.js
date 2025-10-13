/* -----------------------------
   CONFIG — add your OpenWeatherMap API key here
   Get one at: https://openweathermap.org/
------------------------------ */
const API_KEY = "6516f6e4211ec2694dc5d961951c0cb6"; // <-- paste your OpenWeatherMap API key here

/* Update intervals */
const WEATHER_REFRESH_MS = 60 * 10000000; // 1 minute
const CLOCK_UPDATE_MS = 1000;

/* ---------- Clock & Date ---------- */
function updateClock() {
  const now = new Date();
  const hh = String(now.getHours()).padStart(2, "0");
  const mm = String(now.getMinutes()).padStart(2, "0");
  const ss = String(now.getSeconds()).padStart(2, "0");

  // center time with pulsing colons
  const timeHtml = `${hh}<span class="pulse">:</span>${mm}<span class="pulse">:</span>${ss}`;
  document.getElementById("time").innerHTML = timeHtml;

  // weekday in elegant format
  const weekday = now.toLocaleDateString(undefined, { weekday: "long" });
  document.getElementById("weekday").textContent = weekday;

  // date e.g. "4th November 2025"
  const day = now.getDate();
  const daySuffix = (d => {
    if (d >= 11 && d <= 13) return "th";
    switch (d % 10) {
      case 1: return "st";
      case 2: return "nd";
      case 3: return "rd";
      default: return "th";
    }
  })(day);
  const monthYear = now.toLocaleDateString(undefined, { month: "long", year: "numeric" });
  document.getElementById("date").textContent = `${day}${daySuffix} ${monthYear}`;
}
updateClock();
setInterval(updateClock, CLOCK_UPDATE_MS);

/* ---------- Weather (automatic location) ---------- */
async function displayWeather(tempC, desc, place, icon) {
  document.getElementById("w-temp").textContent = `${Math.round(tempC)}°C`;
  document.getElementById("w-desc").textContent = desc || "—";
  document.getElementById("w-place").textContent = place || "—";
  // simple icon (could replace with svg set)
  const ico = document.getElementById("w-icon");
  if (icon) ico.textContent = icon;
}

async function fetchWeatherByCoords(lat, lon) {
  if (!API_KEY) {
    // fallback decorative values if no key
    const now = new Date();
    const hrs = now.getHours();
    const temp = Math.round(8 + Math.sin((hrs/24)*Math.PI*2) * 6);
    const desc = (hrs > 6 && hrs < 19) ? "clear" : "calm";
    displayWeather(temp, desc, "Local time", "☀️");
    return;
  }

  try {
    const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${API_KEY}`;
    const res = await fetch(url);
    if (!res.ok) throw new Error("Failed weather fetch");
    const data = await res.json();
    const temp = data.main.temp;
    const desc = data.weather && data.weather[0] ? data.weather[0].description : "";
    const place = `${data.name}${data.sys && data.sys.country ? ", "+data.sys.country : ""}`;
    const icon = mapOWIconToEmoji(data.weather && data.weather[0] ? data.weather[0].icon : null);
    displayWeather(temp, desc, place, icon);
  } catch (err) {
    console.warn("Weather error:", err);
  }
}

function mapOWIconToEmoji(iconCode) {
  if (!iconCode) return "⛅";
  if (iconCode.startsWith("01")) return "☀️";
  if (iconCode.startsWith("02")) return "🌤️";
  if (iconCode.startsWith("03") || iconCode.startsWith("04")) return "☁️";
  if (iconCode.startsWith("09") || iconCode.startsWith("10")) return "🌧️";
  if (iconCode.startsWith("11")) return "⛈️";
  if (iconCode.startsWith("13")) return "❄️";
  if (iconCode.startsWith("50")) return "🌫️";
  return "☁️";
}

/* Try browser geolocation. If denied or fails, fallback to IP-based lookup */
async function determineLocationAndFetchWeather() {
  if (navigator.geolocation) {
    // try with permission and timeout
    const geoPromise = new Promise((resolve, reject) => {
      const opts = { enableHighAccuracy: false, timeout: 8000, maximumAge: 1000 * 60 * 5 };
      navigator.geolocation.getCurrentPosition(
        pos => resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude }),
        err => reject(err),
        opts
      );
    });

    try {
      const coords = await geoPromise;
      await fetchWeatherByCoords(coords.lat, coords.lon);
      return;
    } catch (e) {
      console.warn("Geolocation failed or denied:", e);
      // continue to IP fallback
    }
  }

  // IP-based fallback using ipinfo.io or ipapi.co
  try {
    // ipapi.co is free for basic requests; might be rate-limited
    const r = await fetch("https://ipapi.co/json/");
    if (!r.ok) throw new Error("IP location failed");
    const info = await r.json();
    if (info && info.latitude && info.longitude) {
      await fetchWeatherByCoords(info.latitude, info.longitude);
      return;
    }
    // some services use 'lat'/'lon' names:
    if (info && info.lat && info.lon) {
      await fetchWeatherByCoords(info.lat, info.lon);
      return;
    }
    // if service returns city/country only, call weather by city:
    if (info && info.city) {
      if (!API_KEY) {
        displayWeather(12, "—", info.city, "☁️");
        return;
      }
      const url = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(info.city)}&units=metric&appid=${API_KEY}`;
      const r2 = await fetch(url);
      const d2 = await r2.json();
      if (d2 && d2.coord) {
        await fetchWeatherByCoords(d2.coord.lat, d2.coord.lon);
        return;
      }
    }
  } catch (e) {
    console.warn("IP location fallback failed:", e);
  }

  // final fallback: show placeholder
  displayWeather(12, "—", "Unknown", "☁️");
}

/* periodically refresh weather */
async function initWeatherCycle() {
  await determineLocationAndFetchWeather();
  setInterval(determineLocationAndFetchWeather, WEATHER_REFRESH_MS);
}
initWeatherCycle();

/* ---------- Particles: subtle floating orbs ---------- */
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");
let W = canvas.width = innerWidth;
let H = canvas.height = innerHeight;

window.addEventListener("resize", () => {
  W = canvas.width = innerWidth;
  H = canvas.height = innerHeight;
  initParticles();
});

function rand(min, max){ return Math.random()*(max-min)+min; }
let particles = [];

function initParticles(){
  particles = [];
  const density = Math.max(12, Math.round((W*H)/120000)); // scale density by screen
  for (let i=0;i<density;i++){
    particles.push({
      x: rand(0, W),
      y: rand(0, H),
      r: rand(0.6, 2.6),
      vx: rand(-0.12, 0.12),
      vy: rand(-0.04, 0.06),
      alpha: rand(0.04, 0.22)
    });
  }
}
initParticles();

function drawParticles(){
  ctx.clearRect(0,0,W,H);
  for (const p of particles){
    p.x += p.vx;
    p.y += p.vy;

    // tiny oscillation
    p.vx += Math.sin((p.x + p.y) * 0.0005) * 0.0006;

    // wrap edges
    if (p.x < -30) p.x = W + 30;
    if (p.x > W + 30) p.x = -30;
    if (p.y < -30) p.y = H + 30;
    if (p.y > H + 30) p.y = -30;

    ctx.beginPath();
    ctx.fillStyle = `rgba(255,240,210,${p.alpha})`;
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fill();
  }
  requestAnimationFrame(drawParticles);
}
drawParticles();

/* Parallax subtle background movement on mouse for luxury feel */
let targetX = 0, targetY = 0;
window.addEventListener("mousemove", (e) => {
  const cx = (e.clientX / W) - 0.5;
  const cy = (e.clientY / H) - 0.5;
  targetX = cx * 20;
  targetY = cy * 12;
});
function parallaxLoop(){
  const bg = document.querySelector(".bg");
  const tx = (targetX * 0.04);
  const ty = (targetY * 0.04);
  bg.style.transform = `scale(1.04) translate(${tx}px, ${ty}px)`;
  requestAnimationFrame(parallaxLoop);
}
parallaxLoop();
