const TrashDealApp = (() => {
  const API_BASE = `${window.location.origin}/api`;
  const TOKEN_KEY = "trashdeal_token";
  const USER_KEY = "trashdeal_user";
  const SCAN_RESULT_KEY = "trashdeal_scan_result";

  const getToken = () => localStorage.getItem(TOKEN_KEY);
  const setToken = (token) => localStorage.setItem(TOKEN_KEY, token);
  const clearToken = () => localStorage.removeItem(TOKEN_KEY);
  const getUser = () => {
    try {
      return JSON.parse(localStorage.getItem(USER_KEY) || "null");
    } catch {
      return null;
    }
  };
  const setUser = (user) => localStorage.setItem(USER_KEY, JSON.stringify(user));
  const clearUser = () => localStorage.removeItem(USER_KEY);
  const clearAuth = () => {
    clearToken();
    clearUser();
  };
  const getScanResult = () => {
    try {
      return JSON.parse(localStorage.getItem(SCAN_RESULT_KEY) || "null");
    } catch {
      return null;
    }
  };
  const setScanResult = (value) => localStorage.setItem(SCAN_RESULT_KEY, JSON.stringify(value));
  const getDefaultRoute = (user = getUser()) => user?.role === "collector" ? "/collector/dashboard" : "/dashboard";

  async function request(path, options = {}) {
    const headers = { Accept: "application/json", ...(options.headers || {}) };
    const token = getToken();
    if (token) headers.Authorization = `Bearer ${token}`;

    let body = options.body;
    if (body && !(body instanceof FormData)) {
      headers["Content-Type"] = "application/json";
      body = JSON.stringify(body);
    }

    const response = await fetch(`${API_BASE}${path}`, { ...options, headers, body });

    let data = {};
    try {
      data = await response.json();
    } catch {
      data = { success: false, message: "Unexpected response from server." };
    }

    if (!response.ok) {
      let message = data.message || "Request failed.";
      if (data.errors) {
        const firstError = Object.values(data.errors)[0];
        if (Array.isArray(firstError) && firstError[0]) message = firstError[0];
      }
      throw new Error(message);
    }

    return data;
  }

  function setMessage(target, message, type = "success") {
    if (!target) return;
    target.className = `status-box${type === "error" ? " error" : ""}`;
    target.textContent = message;
    target.classList.remove("hidden");
  }

  function showToast(message, type = "success") {
    let stack = document.getElementById("toast-stack");
    if (!stack) {
      stack = document.createElement("div");
      stack.id = "toast-stack";
      stack.className = "toast-stack";
      document.body.appendChild(stack);
    }

    const toast = document.createElement("div");
    toast.className = `toast ${type === "error" ? "error" : "success"}`;
    toast.textContent = message;
    stack.appendChild(toast);

    window.setTimeout(() => toast.classList.add("visible"), 10);
    window.setTimeout(() => {
      toast.classList.remove("visible");
      window.setTimeout(() => toast.remove(), 250);
    }, 2400);
  }

  function hideMessage(target) {
    if (!target) return;
    target.classList.add("hidden");
  }

  function escapeHtml(value) {
    return String(value ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#39;");
  }

  function formatDate(value) {
    if (!value) return "Not active";
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? value : date.toLocaleDateString(undefined, {
      day: "numeric",
      month: "short",
      year: "numeric",
    });
  }

  function pickupStatusLabel(status) {
    return ({
      pending: "Pending",
      assigned: "Pending",
      in_progress: "In Progress",
      completed: "Completed",
      cancelled: "Cancelled",
    })[status] || status;
  }

  function pickupStatusClass(status) {
    return ({
      pending: "status-pending",
      assigned: "status-pending",
      in_progress: "status-progress",
      completed: "status-completed",
      cancelled: "status-cancelled",
    })[status] || "";
  }

  function premiumLabel(user) {
    if (!user?.is_premium) return "Standard";
    return user.premium_plan === "annual" ? "Premium Annual" : "Premium Monthly";
  }

  async function fetchProfile() {
    const profile = await request("/profile");
    setUser(profile.user);
    updateHeaderState();
    return profile.user;
  }

  async function fetchPremiumPlans() {
    return request("/premium/plans");
  }

  function setLoading(button, loading, idleLabel = null) {
    if (!button) return;
    if (!button.dataset.label) {
      button.dataset.label = idleLabel || button.textContent;
    }
    button.disabled = loading;
    button.textContent = loading ? "Please wait..." : (idleLabel || button.dataset.label);
  }

  function updateHeaderState() {
    const authed = !!getToken();
    const user = getUser();
    document.querySelectorAll("[data-auth-only]").forEach((el) => el.classList.toggle("hidden", !authed));
    document.querySelectorAll("[data-guest-only]").forEach((el) => el.classList.toggle("hidden", authed));
    document.querySelectorAll("[data-user-name]").forEach((el) => {
      el.textContent = user ? user.name : "Guest";
    });
    document.querySelectorAll("[data-plan-label]").forEach((el) => {
      el.textContent = premiumLabel(user);
    });
    document.querySelectorAll("[data-premium-state]").forEach((el) => {
      el.textContent = user?.is_premium ? "Premium active" : "Premium locked";
    });
    document.querySelectorAll("[data-premium-pill]").forEach((el) => {
      el.classList.toggle("is-active", !!user?.is_premium);
      el.textContent = user?.is_premium ? "Premium" : "Standard";
    });
  }

  function bindGlobal() {
    updateHeaderState();
    document.querySelectorAll("[data-mobile-nav-toggle]").forEach((button) => {
      button.addEventListener("click", () => {
        const nav = button.parentElement?.querySelector(".top-nav");
        if (!nav) return;
        const isOpen = nav.classList.toggle("is-open");
        button.setAttribute("aria-expanded", isOpen ? "true" : "false");
        button.textContent = isOpen ? "Close" : "Menu";
      });
    });
    document.querySelectorAll("[data-logout]").forEach((button) => {
      button.addEventListener("click", async () => {
        try {
          if (getToken()) await request("/logout", { method: "POST" });
        } catch {}
        clearAuth();
        updateHeaderState();
        window.location.href = "/login";
      });
    });
    document.querySelectorAll(".top-nav a, .top-nav button[data-logout]").forEach((item) => {
      item.addEventListener("click", () => {
        const nav = item.closest(".top-nav");
        const header = item.closest(".site-header-inner");
        const toggle = header?.querySelector("[data-mobile-nav-toggle]");
        if (!nav || !toggle || window.innerWidth > 720) return;
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.textContent = "Menu";
      });
    });
  }

  function requireAuth(redirect = "/login") {
    if (!getToken()) {
      window.location.href = redirect;
      return false;
    }
    return true;
  }

  function formatPickupDate(value) {
    if (!value) return "Not scheduled";
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? value : date.toLocaleString();
  }

  function formatDateTimeLocalValue(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${year}-${month}-${day}T${hours}:${minutes}`;
  }

  function serializePickupTime(value) {
    if (!value) return value;
    const localDate = new Date(value);
    return Number.isNaN(localDate.getTime()) ? value : localDate.toISOString();
  }

  function formatPickupCountdown(value) {
    if (!value) return "";
    const target = new Date(value);
    const diff = target.getTime() - Date.now();

    if (Number.isNaN(target.getTime()) || diff <= 0) {
      return "Completing now";
    }

    const totalMinutes = Math.ceil(diff / 60000);
    if (totalMinutes < 60) {
      return `Completes in ${totalMinutes} min`;
    }

    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return `Completes in ${hours}h ${minutes}m`;
  }

  function renderItems(target, html) {
    if (target) target.innerHTML = html;
  }

  function enhanceQrCodes(scope = document) {
    if (!window.QRCode) return;

    scope.querySelectorAll("[data-qr-code]").forEach((node) => {
      const value = node.getAttribute("data-qr-code");
      if (!value || node.dataset.rendered === "true") return;

      node.innerHTML = "";
      new window.QRCode(node, {
        text: value,
        width: 132,
        height: 132,
        colorDark: "#112917",
        colorLight: "#ffffff",
        correctLevel: window.QRCode.CorrectLevel.M,
      });
      node.dataset.rendered = "true";
    });
  }

  function loadExternalScript(src) {
    return new Promise((resolve, reject) => {
      const existing = document.querySelector(`script[src="${src}"]`);
      if (existing) {
        existing.addEventListener("load", resolve, { once: true });
        existing.addEventListener("error", reject, { once: true });
        if (existing.dataset.ready === "true") resolve();
        return;
      }

      const script = document.createElement("script");
      script.src = src;
      script.async = true;
      script.addEventListener("load", () => {
        script.dataset.ready = "true";
        resolve();
      }, { once: true });
      script.addEventListener("error", reject, { once: true });
      document.head.appendChild(script);
    });
  }

  function normalizeDetectedWaste(label) {
    const lower = String(label || "").toLowerCase();
    if (lower.includes("banana") || lower.includes("fruit") || lower.includes("food") || lower.includes("organic")) {
      return { detectedType: "organic", pickupWasteType: "organic" };
    }
    if (lower.includes("metal") || lower.includes("can") || lower.includes("tin")) {
      return { detectedType: "metal", pickupWasteType: "recyclable" };
    }
    if (lower.includes("glass") || lower.includes("jar")) {
      return { detectedType: "glass", pickupWasteType: "recyclable" };
    }
    return { detectedType: "plastic", pickupWasteType: "recyclable" };
  }

  function showSuccessAnimation(message = "🎉 Reward Redeemed!") {
    const el = document.createElement("div");
    el.className = "success-pop";
    el.textContent = message;
    document.body.appendChild(el);

    window.setTimeout(() => {
      el.classList.add("visible");
    }, 10);

    window.setTimeout(() => {
      el.classList.remove("visible");
      window.setTimeout(() => el.remove(), 250);
    }, 1800);
  }

  function bindPremiumButtons({ scope = document, statusTarget = null, onSuccess = null } = {}) {
    scope.querySelectorAll("[data-subscribe-plan]").forEach((button) => {
      button.addEventListener("click", async () => {
        const plan = button.getAttribute("data-subscribe-plan");
        hideMessage(statusTarget);
        setLoading(button, true);

        try {
          const data = await request("/premium/subscribe", {
            method: "POST",
            body: { plan },
          });
          const user = await fetchProfile();
          setMessage(statusTarget, `${data.message} Active until ${formatDate(user.premium_expires_at)}.`);
          showToast(data.message);
          if (typeof onSuccess === "function") {
            await onSuccess();
          }
        } catch (error) {
          setMessage(statusTarget, error.message, "error");
          showToast(error.message, "error");
        } finally {
          setLoading(button, false);
        }
      });
    });
  }

  function bindPremiumCancelButtons({ scope = document, statusTarget = null, onSuccess = null } = {}) {
    scope.querySelectorAll("[data-cancel-premium]").forEach((button) => {
      button.addEventListener("click", async () => {
        hideMessage(statusTarget);
        setLoading(button, true, "Cancel premium");

        try {
          const data = await request("/premium/cancel", { method: "POST" });
          await fetchProfile();
          setMessage(statusTarget, data.message);
          showToast(data.message);
          if (typeof onSuccess === "function") {
            await onSuccess();
          }
        } catch (error) {
          setMessage(statusTarget, error.message, "error");
          showToast(error.message, "error");
        } finally {
          setLoading(button, false, "Cancel premium");
        }
      });
    });
  }

  async function initLoginPage() {
    if (getToken()) {
      window.location.href = getDefaultRoute();
      return;
    }
    const form = document.getElementById("login-form");
    const status = document.getElementById("auth-status");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");
    if (!form) return;

    togglePassword?.addEventListener("click", () => {
      const showPassword = passwordInput?.type === "password";
      if (passwordInput) {
        passwordInput.type = showPassword ? "text" : "password";
      }
      togglePassword.textContent = showPassword ? "Hide" : "Show";
      togglePassword.setAttribute("aria-label", showPassword ? "Hide password" : "Show password");
    });

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      hideMessage(status);
      const formData = new FormData(form);
      try {
        const data = await request("/login", {
          method: "POST",
          body: { login: formData.get("login"), password: formData.get("password") },
        });
        setToken(data.token);
        setUser(data.user);
        updateHeaderState();
        showToast("Welcome back!");
        window.location.href = getDefaultRoute(data.user);
      } catch (error) {
        setMessage(status, error.message, "error");
        showToast(error.message, "error");
      }
    });
  }

  async function initRegisterPage() {
    if (getToken()) {
      window.location.href = getDefaultRoute();
      return;
    }
    const form = document.getElementById("register-form");
    const status = document.getElementById("auth-status");
    if (!form) return;

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      hideMessage(status);
      const formData = new FormData(form);
      try {
        const data = await request("/register", {
          method: "POST",
          body: {
            name: formData.get("name"),
            phone: formData.get("phone"),
            email: formData.get("email") || null,
            password: formData.get("password"),
            address: formData.get("address") || null,
            latitude: formData.get("latitude") || null,
            longitude: formData.get("longitude") || null,
          },
        });
        setToken(data.token);
        setUser(data.user);
        updateHeaderState();
        showToast("Account created successfully.");
        window.location.href = getDefaultRoute(data.user);
      } catch (error) {
        setMessage(status, error.message, "error");
        showToast(error.message, "error");
      }
    });
  }

  async function initDashboardPage() {
    if (!requireAuth()) return;
    const currentUser = getUser();
    if (currentUser?.role === "collector") {
      window.location.href = "/collector/dashboard";
      return;
    }
    const [user, points, rewards, pickups, stats] = await Promise.all([
      fetchProfile(),
      request("/points"),
      request("/rewards"),
      request("/pickups"),
      request("/dashboard/stats"),
    ]);

    document.getElementById("metric-points").textContent = points.points ?? 0;
    document.getElementById("metric-rewards").textContent = (rewards.rewards || []).length;
    document.getElementById("metric-pickups").textContent = (pickups.pickups?.data || []).length;
    document.getElementById("metric-role").textContent = user.role || "user";
    document.getElementById("welcome-name").textContent = user.name || "User";
    document.getElementById("profile-snippet").textContent = `${user.phone || ""}${user.email ? ` • ${user.email}` : ""}`;

    renderItems(document.getElementById("dashboard-rewards"), (rewards.rewards || []).slice(0, 4).map((reward) => `
      <div class="item">
        <div class="item-top">
          <div>
            <p class="item-title">${escapeHtml(reward.name)}</p>
            <div class="item-meta">${reward.can_redeem ? "Ready to redeem 🎁" : `Earn ${reward.points_remaining} more points to unlock`}</div>
          </div>
          <span class="badge">${reward.points_required} pts</span>
        </div>
      </div>
    `).join("") || `<div class="item"><div class="item-meta">${(points.points ?? 0) === 0 ? "Complete your first pickup to start earning points 🚀" : "Start recycling to unlock exciting rewards 🌱"}</div></div>`);

    renderItems(document.getElementById("dashboard-pickups"), (pickups.pickups?.data || []).slice(0, 4).map((pickup) => `
      <div class="item">
        <div class="item-top">
          <div>
            <p class="item-title">${escapeHtml(pickup.waste_type)} pickup</p>
            <div class="item-meta">${escapeHtml(pickup.address)}</div>
          </div>
            <span class="badge ${pickupStatusClass(pickup.status)}">${escapeHtml(pickupStatusLabel(pickup.status))}</span>
          </div>
          <div class="item-meta">Scheduled: ${formatPickupDate(pickup.scheduled_at)}</div>
      </div>
    `).join("") || `<div class="item"><div class="item-meta">Schedule your first pickup and start building your points.</div></div>`);

    document.getElementById("analytics-total-pickups").textContent = stats.total_pickups ?? 0;
    document.getElementById("analytics-total-weight").textContent = `${Number(stats.total_weight ?? 0).toFixed(1)} kg`;
    document.getElementById("analytics-rewards-redeemed").textContent = stats.rewards_redeemed ?? 0;

    if (window.Chart) {
      const pickupTrendCanvas = document.getElementById("pickup-trends-chart");
      const wasteCanvas = document.getElementById("waste-distribution-chart");
      const pointsCanvas = document.getElementById("points-over-time-chart");

      const chartDefaults = {
        borderColor: "#2fb757",
        backgroundColor: "rgba(47, 183, 87, 0.18)",
      };

      if (pickupTrendCanvas) {
        new window.Chart(pickupTrendCanvas, {
          type: "line",
          data: {
            labels: (stats.monthly_pickups || []).map((item) => item.label),
            datasets: [{
              label: "Pickups",
              data: (stats.monthly_pickups || []).map((item) => item.total),
              tension: 0.36,
              fill: true,
              borderWidth: 3,
              ...chartDefaults,
            }],
          },
          options: { responsive: true, plugins: { legend: { display: false } } },
        });
      }

      if (wasteCanvas) {
        new window.Chart(wasteCanvas, {
          type: "pie",
          data: {
            labels: (stats.waste_distribution || []).map((item) => item.label),
            datasets: [{
              data: (stats.waste_distribution || []).map((item) => item.total),
              backgroundColor: ["#2fb757", "#f2c65f", "#1f8a3e", "#4e9f84", "#9fd1a6"],
            }],
          },
          options: { responsive: true },
        });
      }

      if (pointsCanvas) {
        new window.Chart(pointsCanvas, {
          type: "bar",
          data: {
            labels: (stats.points_over_time || []).map((item) => item.label),
            datasets: [{
              label: "Points",
              data: (stats.points_over_time || []).map((item) => item.total),
              backgroundColor: "#2fb757",
              borderRadius: 10,
            }],
          },
          options: { responsive: true, plugins: { legend: { display: false } } },
        });
      }
    }
  }

  async function initPickupsPage() {
    if (!requireAuth()) return;
    const currentUser = getUser();
    if (currentUser?.role === "collector") {
      window.location.href = "/collector/dashboard";
      return;
    }
    const form = document.getElementById("pickup-form");
    const status = document.getElementById("pickup-status");
    const list = document.getElementById("pickup-list");
    const pickupDateInput = document.getElementById("pickup-date");
    const pickupLatInput = document.getElementById("pickup-lat");
    const pickupLngInput = document.getElementById("pickup-lng");
    const locationButton = document.getElementById("use-location");
    const locationStatus = document.getElementById("pickup-location-status");
    const wasteTypeSelect = document.getElementById("waste-type");
    const scanFillText = document.getElementById("scan-fill-text");

    const scanResult = getScanResult();
    if (scanResult && wasteTypeSelect) {
      wasteTypeSelect.value = scanResult.pickupWasteType;
      if (scanFillText) {
        scanFillText.textContent = `AI suggestion: ${scanResult.detectedType} detected with ${scanResult.confidence}% confidence. Waste type set to ${scanResult.pickupWasteType}.`;
      }
    }

    if (pickupDateInput) {
      const minDate = new Date(Date.now() + 5 * 60000);
      pickupDateInput.min = formatDateTimeLocalValue(minDate);
    }

    locationButton?.addEventListener("click", () => {
      if (!navigator.geolocation) {
        setMessage(locationStatus, "Location is not supported on this device.", "error");
        showToast("Location is not supported on this device.", "error");
        return;
      }

      hideMessage(locationStatus);
      setLoading(locationButton, true, "Use my location");
      navigator.geolocation.getCurrentPosition((position) => {
        pickupLatInput.value = position.coords.latitude.toFixed(6);
        pickupLngInput.value = position.coords.longitude.toFixed(6);
        setMessage(locationStatus, "Location added. We will assign the closest collector.");
        showToast("Location added successfully.");
        setLoading(locationButton, false, "Use my location");
      }, () => {
        setMessage(locationStatus, "We could not get your location. You can still schedule the pickup.", "error");
        showToast("We could not get your location.", "error");
        setLoading(locationButton, false, "Use my location");
      });
    });

    async function loadPickups() {
      const data = await request("/pickups");
      const previousStatuses = JSON.parse(list?.dataset.statuses || "{}");
      const nextStatuses = {};

      renderItems(list, (data.pickups?.data || []).map((pickup) => {
        nextStatuses[pickup.id] = pickup.status;
        if (previousStatuses[pickup.id] && previousStatuses[pickup.id] !== pickup.status) {
          const toastMap = {
            assigned: "Collector assigned to your pickup.",
            in_progress: "Collector is on the way.",
            completed: "Pickup completed successfully.",
          };
          if (toastMap[pickup.status]) {
            showToast(toastMap[pickup.status]);
          }
        }

        return `
        <div class="item">
          <div class="item-top">
            <div>
              <p class="item-title">${escapeHtml(pickup.waste_type)} pickup</p>
              <div class="item-meta">${escapeHtml(pickup.address)}</div>
            </div>
            <span class="badge ${pickupStatusClass(pickup.status)}">${escapeHtml(pickupStatusLabel(pickup.status))}</span>
          </div>
          <div class="item-meta">Your selected time: ${formatPickupDate(pickup.scheduled_at)}</div>
          ${pickup.collector ? `<div class="item-meta">Assigned collector: ${escapeHtml(pickup.collector.name || pickup.collector.user?.name || "Collector")}</div>` : `<div class="item-meta">We are matching the nearest collector for this request.</div>`}
          ${(pickup.status === "assigned" || pickup.status === "in_progress") ? `
            <div class="pickup-qr-card">
              <div class="pickup-qr-label">Pickup verification code</div>
              <div class="pickup-qr-visual" data-qr-code="${escapeHtml(pickup.qr_token || "")}"></div>
              <div class="pickup-qr-token">${escapeHtml(pickup.qr_token || "--")}</div>
              <div class="item-meta">Show this code to your collector at pickup time.</div>
            </div>
          ` : ""}
          <div class="inline-actions" style="margin-top:14px;">
            <a href="/tracking?pickup=${pickup.id}" class="button secondary">Track live</a>
          </div>
          <div class="item-meta">Estimated: ${pickup.estimated_weight_kg || "-"} kg</div>
        </div>
      `;
      }).join("") || `<div class="item"><div class="item-meta">No pickups yet.</div></div>`);

      if (list) {
        list.dataset.statuses = JSON.stringify(nextStatuses);
        enhanceQrCodes(list);
      }
    }

    await loadPickups();
    window.setInterval(() => {
      loadPickups().catch(() => {});
    }, 5000);

    form?.addEventListener("submit", async (event) => {
      event.preventDefault();
      hideMessage(status);
      const formData = new FormData(form);
      try {
        const rawScheduledAt = formData.get("scheduled_at");
        await request("/pickups", {
          method: "POST",
          body: {
            address: formData.get("address"),
            waste_type: formData.get("waste_type"),
            scheduled_at: serializePickupTime(rawScheduledAt),
            pickup_lat: formData.get("pickup_lat") || null,
            pickup_lng: formData.get("pickup_lng") || null,
            estimated_weight_kg: formData.get("estimated_weight_kg") || null,
            notes: formData.get("notes") || null,
          },
        });
        const selectedTime = formatPickupDate(rawScheduledAt);
        form.reset();
        if (pickupDateInput) {
          const minDate = new Date(Date.now() + 5 * 60000);
          pickupDateInput.min = formatDateTimeLocalValue(minDate);
        }
        hideMessage(locationStatus);
        setMessage(status, `Pickup scheduled for ${selectedTime}. Your collector will verify the pickup after that time.`);
        showToast("Pickup scheduled successfully.");
        await loadPickups();
      } catch (error) {
        setMessage(status, error.message, "error");
        showToast(error.message, "error");
      }
    });
  }

  async function initRewardsPage() {
    if (!requireAuth()) return;
    const currentUser = getUser();
    if (currentUser?.role === "collector") {
      window.location.href = "/collector/dashboard";
      return;
    }
    const list = document.getElementById("reward-list");
    const status = document.getElementById("reward-status");
    const pointsEl = document.getElementById("reward-points");
    const filterBar = document.getElementById("reward-filters");
    let activeCategory = "all";
    let allRewards = [];

    function syncRewardFilterState() {
      filterBar?.querySelectorAll("[data-filter-category]").forEach((node) => {
        node.classList.toggle("is-active", node.getAttribute("data-filter-category") === activeCategory);
      });
    }

    function renderRewards() {
      const filteredRewards = allRewards.filter((reward) => activeCategory === "all" || reward.category === activeCategory);
      const rewardsToShow = filteredRewards.length > 0 ? filteredRewards : allRewards;

      if (filteredRewards.length === 0 && activeCategory !== "all" && allRewards.length > 0) {
        setMessage(status, `No ${activeCategory} rewards yet. Showing all rewards instead.`, "error");
      } else {
        hideMessage(status);
      }

      renderItems(list, rewardsToShow.map((reward) => `
        <div class="item reward-card${reward.is_locked ? " is-locked" : " is-unlocked"}">
          <div class="item-top">
            <div>
              <p class="item-title">${escapeHtml(reward.name)}</p>
              <div class="item-meta">${escapeHtml(reward.category)} category${reward.is_premium_only ? " • Premium reward" : ""}</div>
            </div>
            <span class="badge">${reward.points_required} pts</span>
          </div>
          <div class="reward-state ${reward.is_locked ? "locked" : "unlocked"}">
            ${reward.is_locked ? "Locked 🔒" : "Ready to redeem 🎁"}
          </div>
          <div class="item-meta">
            ${reward.is_locked
              ? (reward.lock_reason === "Premium required"
                ? "Premium membership required to unlock this reward."
                : `Earn ${reward.points_remaining} more points to unlock`)
              : "Ready to redeem 🎁"}
          </div>
          <div class="inline-actions">
            <button class="button ${reward.can_redeem ? "primary" : "secondary button-disabled"}" data-redeem="${reward.id}" ${reward.can_redeem ? "" : "disabled"}>
              ${reward.can_redeem ? "Redeem" : "Locked 🔒"}
            </button>
          </div>
        </div>
      `).join("") || `<div class="item"><div class="item-meta">No rewards match this category yet.</div></div>`);

      list.querySelectorAll("[data-redeem]").forEach((button) => {
        button.addEventListener("click", async () => {
          hideMessage(status);
          setLoading(button, true, "Redeem reward");
          try {
            const data = await request(`/rewards/${button.getAttribute("data-redeem")}/redeem`, { method: "POST" });
            setMessage(status, `🎉 Reward Redeemed! Reference: ${data.reference_code}`);
            showSuccessAnimation("🎉 Reward Redeemed!");
            showToast("Reward redeemed successfully!");
            await loadRewards();
          } catch (error) {
            setMessage(status, error.message, "error");
            showToast(error.message, "error");
          } finally {
            setLoading(button, false, "Redeem reward");
          }
        });
      });
    }

    async function loadRewards() {
      const [points, rewards] = await Promise.all([
        request("/points"),
        request("/rewards"),
      ]);
      pointsEl.textContent = points.points ?? 0;
      allRewards = rewards.rewards || [];
      if ((points.points ?? 0) === 0 && allRewards.length > 0) {
        setMessage(status, "Complete your first pickup to start earning points 🚀");
      }
      renderRewards();
    }

    filterBar?.querySelectorAll("[data-filter-category]").forEach((button) => {
      button.addEventListener("click", () => {
        activeCategory = button.getAttribute("data-filter-category") || "all";
        syncRewardFilterState();
        renderRewards();
      });
    });

    syncRewardFilterState();
    await loadRewards();
  }

  async function initCollectorDashboardPage() {
    if (!requireAuth()) return;
    const currentUser = getUser();
    if (currentUser?.role !== "collector") {
      window.location.href = "/dashboard";
      return;
    }

    const profile = await fetchProfile();
    if (profile.role !== "collector") {
      window.location.href = "/dashboard";
      return;
    }

    const nameEl = document.getElementById("collector-name");
    const totalEl = document.getElementById("collector-total");
    const progressEl = document.getElementById("collector-progress");
    const completedEl = document.getElementById("collector-completed");
    const status = document.getElementById("collector-status");
    const list = document.getElementById("collector-pickups");

    if (nameEl) nameEl.textContent = profile.name || "Collector";

    if (navigator.geolocation) {
      const pushCollectorLocation = () => {
        navigator.geolocation.getCurrentPosition(async (position) => {
          try {
            await request("/collector/location", {
              method: "POST",
              body: {
                latitude: Number(position.coords.latitude.toFixed(6)),
                longitude: Number(position.coords.longitude.toFixed(6)),
              },
            });
          } catch {}
        });
      };

      pushCollectorLocation();
      window.setInterval(pushCollectorLocation, 5000);
    }

    async function loadPickups() {
      const data = await request("/assigned-pickups");
      const pickups = data.pickups || [];
      const inProgressCount = pickups.filter((pickup) => pickup.status === "in_progress").length;
      const completedCount = pickups.filter((pickup) => pickup.status === "completed").length;

      if (totalEl) totalEl.textContent = pickups.length;
      if (progressEl) progressEl.textContent = inProgressCount;
      if (completedEl) completedEl.textContent = completedCount;

      renderItems(list, pickups.map((pickup) => `
        <article class="collector-card ${pickup.status === "completed" ? "is-complete" : ""}">
          <div class="collector-card-head">
            <div>
              <p class="item-title">${escapeHtml(pickup.user?.name || "User pickup")}</p>
              <div class="item-meta">${escapeHtml(pickup.waste_type)} pickup</div>
            </div>
            <span class="badge ${pickupStatusClass(pickup.status)}">${escapeHtml(pickupStatusLabel(pickup.status))}</span>
          </div>
          <div class="collector-meta-grid">
            <div class="collector-meta">
              <span>Location</span>
              <strong>${escapeHtml(pickup.address)}</strong>
            </div>
            <div class="collector-meta">
              <span>Scheduled</span>
              <strong>${formatPickupDate(pickup.scheduled_at)}</strong>
            </div>
            <div class="collector-meta">
              <span>Waste type</span>
              <strong>${escapeHtml(pickup.waste_type)}</strong>
            </div>
            <div class="collector-meta">
              <span>Weight</span>
              <strong>${escapeHtml(pickup.estimated_weight_kg || "-")} kg</strong>
            </div>
          </div>
          <div class="pickup-qr-card compact">
            <div class="pickup-qr-label">Pickup QR token</div>
            <div class="pickup-qr-visual" data-qr-code="${escapeHtml(pickup.qr_token || "")}"></div>
            <div class="pickup-qr-token">${escapeHtml(pickup.qr_token || "--")}</div>
            <div class="item-meta">${pickup.qr_verified_at ? "QR verified. Ready to complete." : "Scan this token after reaching the pickup location."}</div>
          </div>
          <div class="collector-scan-row">
            <input class="input" data-qr-input="${pickup.id}" placeholder="Enter pickup QR token" ${pickup.status === "completed" ? "disabled" : ""}>
            <button class="button secondary" type="button" data-collector-scan="${pickup.id}" ${pickup.status === "completed" ? "disabled" : ""}>Scan QR</button>
          </div>
          <div class="inline-actions collector-actions">
            <button class="button secondary" type="button" data-start-pickup="${pickup.id}" ${(pickup.status !== "assigned") ? "disabled" : ""}>Start Pickup</button>
            <button class="button primary" type="button" data-complete-pickup="${pickup.id}" ${(pickup.status === "completed") ? "disabled" : ""}>Complete Pickup</button>
          </div>
        </article>
      `).join("") || `<div class="item"><div class="item-meta">No assigned pickups yet.</div></div>`);

      enhanceQrCodes(list);

      list.querySelectorAll("[data-start-pickup]").forEach((button) => {
        button.addEventListener("click", async () => {
          hideMessage(status);
          setLoading(button, true, "Start Pickup");
          try {
            const data = await request(`/pickups/${button.getAttribute("data-start-pickup")}/start`, { method: "PATCH" });
            setMessage(status, data.message);
            showToast(data.message);
            await loadPickups();
          } catch (error) {
            setMessage(status, error.message, "error");
            showToast(error.message, "error");
          } finally {
            setLoading(button, false, "Start Pickup");
          }
        });
      });

      list.querySelectorAll("[data-collector-scan]").forEach((button) => {
        button.addEventListener("click", async () => {
          const pickupId = button.getAttribute("data-collector-scan");
          const input = list.querySelector(`[data-qr-input="${pickupId}"]`);
          const token = input?.value?.trim();
          hideMessage(status);

          if (!token) {
            setMessage(status, "Enter the pickup QR token first.", "error");
            showToast("Enter the pickup QR token first.", "error");
            return;
          }

          setLoading(button, true, "Scan QR");
          try {
            const data = await request("/scan/collector-qr", {
              method: "POST",
              body: {
                pickup_id: pickupId,
                qr_token: token,
              },
            });
            setMessage(status, data.message);
            showToast(data.message);
            await loadPickups();
          } catch (error) {
            setMessage(status, error.message, "error");
            showToast(error.message, "error");
          } finally {
            setLoading(button, false, "Scan QR");
          }
        });
      });

      list.querySelectorAll("[data-complete-pickup]").forEach((button) => {
        button.addEventListener("click", async () => {
          hideMessage(status);
          setLoading(button, true, "Complete Pickup");
          try {
            const data = await request(`/pickups/${button.getAttribute("data-complete-pickup")}/complete`, { method: "PATCH" });
            setMessage(status, data.message);
            showToast(data.message);
            await loadPickups();
          } catch (error) {
            setMessage(status, error.message, "error");
            showToast(error.message, "error");
          } finally {
            setLoading(button, false, "Complete Pickup");
          }
        });
      });
    }

    await loadPickups();
    window.setInterval(() => {
      loadPickups().catch(() => {});
    }, 5000);
  }

  async function initScanWastePage() {
    if (!requireAuth()) return;
    const currentUser = getUser();
    if (currentUser?.role === "collector") {
      window.location.href = "/collector/dashboard";
      return;
    }

    const video = document.getElementById("scan-video");
    const canvas = document.getElementById("scan-canvas");
    const status = document.getElementById("scan-status");
    const startButton = document.getElementById("scan-start-camera");
    const stopButton = document.getElementById("scan-stop-camera");
    const captureButton = document.getElementById("scan-capture");
    const detectedTypeEl = document.getElementById("scan-detected-type");
    const confidenceEl = document.getElementById("scan-confidence");
    const pickupTypeEl = document.getElementById("scan-pickup-type");
    let stream = null;
    let model = null;

    const stopCamera = () => {
      if (stream) {
        stream.getTracks().forEach((track) => track.stop());
        stream = null;
      }
      if (video) {
        video.srcObject = null;
      }
    };

    const ensureModel = async () => {
      if (model || !window.mobilenet) return model;
      try {
        model = await window.mobilenet.load();
      } catch {
        model = null;
      }
      return model;
    };

    startButton?.addEventListener("click", async () => {
      try {
        stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: "environment" },
          audio: false,
        });
        video.srcObject = stream;
        setMessage(status, "Camera started. Point it at the waste and capture a frame.");
        showToast("Camera started.");
      } catch (error) {
        setMessage(status, "Camera access was blocked. Please allow camera permission.", "error");
        showToast("Camera access was blocked.", "error");
      }
    });

    stopButton?.addEventListener("click", () => {
      stopCamera();
      setMessage(status, "Camera stopped. You can start it again anytime.");
      showToast("Camera stopped.");
    });

    captureButton?.addEventListener("click", async () => {
      hideMessage(status);
      if (!video.videoWidth) {
        setMessage(status, "Start the camera first to capture a waste image.", "error");
        showToast("Start the camera first.", "error");
        return;
      }

      setLoading(captureButton, true, "Capture and analyze");
      try {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext("2d");
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        let detected = { detectedType: "plastic", pickupWasteType: "recyclable" };
        let confidence = 64;
        const loadedModel = await ensureModel();
        if (loadedModel) {
          const predictions = await loadedModel.classify(canvas);
          if (predictions?.[0]) {
            detected = normalizeDetectedWaste(predictions[0].className);
            confidence = Math.round(predictions[0].probability * 100);
          }
        }

        const blob = await new Promise((resolve) => canvas.toBlob(resolve, "image/jpeg", 0.92));
        const formData = new FormData();
        formData.append("image", blob, `capture-${detected.detectedType}.jpg`);
        formData.append("detected_type", detected.detectedType);
        formData.append("confidence", String(confidence));

        const data = await request("/scan/waste", {
          method: "POST",
          body: formData,
        });

        const scanResult = {
          detectedType: data.category,
          confidence: data.confidence,
          pickupWasteType: data.pickup_waste_type,
        };
        setScanResult(scanResult);

        detectedTypeEl.textContent = data.category;
        confidenceEl.textContent = `${Math.round(data.confidence)}%`;
        pickupTypeEl.textContent = `Pickup form will use ${data.pickup_waste_type}.`;
        setMessage(status, data.message);
        showToast("AI scan complete.");
      } catch (error) {
        setMessage(status, error.message, "error");
        showToast(error.message, "error");
      } finally {
        setLoading(captureButton, false, "Capture and analyze");
      }
    });
  }

  async function initTrackingPage() {
    if (!requireAuth()) return;
    const pickupSelect = document.getElementById("tracking-pickup-select");
    const statusBox = document.getElementById("tracking-status");
    const currentStatusEl = document.getElementById("tracking-current-status");
    const collectorNameEl = document.getElementById("tracking-collector-name");
    let map = null;
    let collectorMarker = null;
    let userMarker = null;
    let routeLine = null;
    let previousStatus = null;

    const initialPickups = await request("/pickups");
    const pickupList = initialPickups.pickups?.data || [];
    const queryPickupId = new URLSearchParams(window.location.search).get("pickup");

    renderItems(pickupSelect, pickupList.map((pickup) => `
      <option value="${pickup.id}" ${String(pickup.id) === String(queryPickupId || pickupList[0]?.id || "") ? "selected" : ""}>
        ${escapeHtml(pickup.address)} • ${escapeHtml(pickupStatusLabel(pickup.status))}
      </option>
    `).join(""));

    if (window.L && !map) {
      map = window.L.map("tracking-map").setView([28.6139, 77.2090], 11);
      window.L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
      }).addTo(map);
    }

    async function loadTracking() {
      const pickupId = pickupSelect?.value;
      if (!pickupId) {
        setMessage(statusBox, "No pickup available to track yet.", "error");
        return;
      }

      const data = await request(`/track/${pickupId}`);
      currentStatusEl.textContent = pickupStatusLabel(data.status);
      collectorNameEl.textContent = data.collector_name || "Not assigned";

      if (previousStatus && previousStatus !== data.status) {
        const toastMap = {
          assigned: "Collector assigned",
          in_progress: "Collector on the way",
          completed: "Pickup completed",
        };
        if (toastMap[data.status]) showToast(toastMap[data.status]);
      }
      previousStatus = data.status;

      if (map && Number.isFinite(data.user_lat) && Number.isFinite(data.user_lng)) {
        const userLatLng = [data.user_lat, data.user_lng];
        const collectorLatLng = Number.isFinite(data.collector_lat) && Number.isFinite(data.collector_lng)
          ? [data.collector_lat, data.collector_lng]
          : null;

        if (!userMarker) {
          userMarker = window.L.marker(userLatLng).addTo(map).bindPopup("Pickup location");
        } else {
          userMarker.setLatLng(userLatLng);
        }

        if (collectorLatLng) {
          if (!collectorMarker) {
            collectorMarker = window.L.marker(collectorLatLng).addTo(map).bindPopup("Collector");
          } else {
            collectorMarker.setLatLng(collectorLatLng);
          }

          const bounds = window.L.latLngBounds([userLatLng, collectorLatLng]);
          map.fitBounds(bounds.pad(0.3));

          if (routeLine) {
            routeLine.setLatLngs([userLatLng, collectorLatLng]);
          } else {
            routeLine = window.L.polyline([userLatLng, collectorLatLng], { color: "#2fb757", weight: 4 }).addTo(map);
          }
        } else {
          map.setView(userLatLng, 14);
        }
      }

      setMessage(statusBox, `Live status: ${pickupStatusLabel(data.status)}. Refreshing every 5 seconds.`);
    }

    pickupSelect?.addEventListener("change", () => {
      loadTracking().catch((error) => {
        setMessage(statusBox, error.message, "error");
      });
    });

    await loadTracking();
    window.setInterval(() => {
      loadTracking().catch(() => {});
    }, 5000);
  }

  async function initProfilePage() {
    if (!requireAuth()) return;
    const [user, points, history, premium] = await Promise.all([
      fetchProfile(),
      request("/points"),
      request("/points/history"),
      fetchPremiumPlans(),
    ]);

    document.getElementById("profile-name").textContent = user.name || "User";
    document.getElementById("profile-role").textContent = user.role || "user";
    document.getElementById("profile-phone").textContent = user.phone || "-";
    document.getElementById("profile-email").textContent = user.email || "-";
    document.getElementById("profile-address").textContent = user.address || "No address added yet";
    document.getElementById("profile-points").textContent = points.points ?? 0;
    document.getElementById("profile-premium").textContent = premiumLabel(user);
    document.getElementById("profile-expiry").textContent = user.is_premium
      ? formatDate(user.premium_expires_at)
      : "Upgrade to activate";

    renderItems(document.getElementById("profile-plan-grid"), (premium.plans || []).map((plan) => `
      <article class="premium-option compact-card${plan.id === "annual" ? " featured" : ""}">
        <div class="premium-option-top">
          <div>
            <span class="eyebrow subtle">${escapeHtml(plan.name)}</span>
            <h3>${escapeHtml(plan.name)} plan</h3>
          </div>
          <span class="premium-price">₹${plan.price_inr}</span>
        </div>
        <p>${escapeHtml(plan.label)}</p>
        <button class="button ${plan.id === "annual" ? "primary" : "secondary"}" data-subscribe-plan="${plan.id}">
          ${user.is_premium && user.premium_plan === plan.id ? "Extend plan" : "Choose this plan"}
        </button>
      </article>
    `).join("") + (user.is_premium ? `
      <article class="compact-card" style="grid-column: 1 / -1;">
        <div class="item-top">
          <div>
            <p class="item-title">Manage your premium plan</p>
            <div class="item-meta">Premium stays active until you cancel it from here.</div>
          </div>
          <span class="badge">${escapeHtml(premiumLabel(user))}</span>
        </div>
        <div class="inline-actions" style="margin-top:18px;">
          <button class="button secondary" data-cancel-premium>Cancel premium</button>
        </div>
      </article>
    ` : ""));

    bindPremiumButtons({
      scope: document.getElementById("profile-plan-grid"),
      statusTarget: document.getElementById("profile-status"),
      onSuccess: initProfilePage,
    });
    bindPremiumCancelButtons({
      scope: document.getElementById("profile-plan-grid"),
      statusTarget: document.getElementById("profile-status"),
      onSuccess: initProfilePage,
    });

    renderItems(document.getElementById("history-list"), (history.transactions?.data || []).map((entry) => `
      <div class="item">
        <div class="item-top">
          <div>
            <p class="item-title">${escapeHtml(entry.type)}</p>
            <div class="item-meta">${escapeHtml(entry.description || "No description")}</div>
          </div>
          <span class="badge">${entry.points} pts</span>
        </div>
        <div class="item-meta">Balance after: ${entry.balance_after}</div>
      </div>
    `).join("") || `<div class="item"><div class="item-meta">No point activity yet.</div></div>`);
  }

  function initLandingPage() {
    updateHeaderState();
  }

  function init() {
    bindGlobal();
    const page = document.body.dataset.page;
    if (page === "landing") initLandingPage();
    if (page === "login") initLoginPage();
    if (page === "register") initRegisterPage();
    if (page === "dashboard") initDashboardPage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "collector-dashboard") initCollectorDashboardPage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "scan-waste") initScanWastePage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "tracking") initTrackingPage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "pickups") initPickupsPage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "rewards") initRewardsPage().catch(() => { clearAuth(); window.location.href = "/login"; });
    if (page === "profile") initProfilePage().catch(() => { clearAuth(); window.location.href = "/login"; });
  }

  return { init };
})();

document.addEventListener("DOMContentLoaded", () => TrashDealApp.init());
