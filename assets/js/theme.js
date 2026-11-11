(function () {
  "use strict";

  var menuButton = document.querySelector(".menu-button");
  var mainNav = document.querySelector(".main-nav");
  if (menuButton && mainNav) {
    menuButton.addEventListener("click", function () {
      var open = mainNav.classList.toggle("open");
      menuButton.setAttribute("aria-expanded", String(open));
    });
    mainNav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        mainNav.classList.remove("open");
        menuButton.setAttribute("aria-expanded", "false");
      });
    });
  }

  function bindFilter(buttonSelector, itemSelector, buttonAttribute, itemAttribute, counterSelector) {
    var buttons = Array.from(document.querySelectorAll(buttonSelector));
    var items = Array.from(document.querySelectorAll(itemSelector));
    var counter = document.querySelector(counterSelector);
    buttons.forEach(function (button) {
      button.addEventListener("click", function () {
        var filter = button.getAttribute(buttonAttribute);
        var visible = 0;
        buttons.forEach(function (candidate) {
          candidate.classList.toggle("active", candidate === button);
        });
        items.forEach(function (item) {
          var values = (item.getAttribute(itemAttribute) || "").split("|");
          var show = filter === "Todos" || values.indexOf(filter) !== -1;
          item.hidden = !show;
          if (show) visible += 1;
        });
        if (counter) counter.textContent = String(visible);
      });
    });
  }

  bindFilter("[data-health-filter]", "[data-health-category]", "data-health-filter", "data-health-category", ".health-count");
  bindFilter("[data-event-filter]", "[data-event-category]", "data-event-filter", "data-event-category", ".event-count");

  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var siteHeader = document.querySelector(".site-header");

  document.querySelectorAll("[data-stone-carousel]").forEach(function (carousel) {
    var slides = Array.from(carousel.querySelectorAll("[data-stone-slide]"));
    var dots = Array.from(carousel.querySelectorAll("[data-stone-dot]"));
    var previous = carousel.querySelector("[data-stone-prev]");
    var next = carousel.querySelector("[data-stone-next]");
    var current = 0;
    var randomQueue = [];
    var timer;
    if (slides.length < 2) return;

    function showStone(index) {
      current = (index + slides.length) % slides.length;
      slides.forEach(function (slide, slideIndex) {
        var active = slideIndex === current;
        slide.classList.toggle("is-active", active);
        slide.setAttribute("aria-hidden", String(!active));
      });
      dots.forEach(function (dot, dotIndex) {
        dot.classList.toggle("is-active", dotIndex === current);
      });
    }

    function nextRandomStone() {
      randomQueue = randomQueue.filter(function (index) { return index !== current; });
      if (!randomQueue.length) {
        randomQueue = slides.map(function (_, index) { return index; }).filter(function (index) { return index !== current; });
        for (var index = randomQueue.length - 1; index > 0; index -= 1) {
          var swapIndex = Math.floor(Math.random() * (index + 1));
          var temporary = randomQueue[index];
          randomQueue[index] = randomQueue[swapIndex];
          randomQueue[swapIndex] = temporary;
        }
      }
      return randomQueue.pop();
    }

    function stopStoneTimer() {
      window.clearInterval(timer);
    }

    function startStoneTimer() {
      stopStoneTimer();
      if (!reduceMotion) timer = window.setInterval(function () { showStone(nextRandomStone()); }, 5000);
    }

    if (previous) previous.addEventListener("click", function () { showStone(current - 1); startStoneTimer(); });
    if (next) next.addEventListener("click", function () { showStone(current + 1); startStoneTimer(); });
    dots.forEach(function (dot, index) {
      dot.addEventListener("click", function () { showStone(index); startStoneTimer(); });
    });
    carousel.addEventListener("mouseenter", stopStoneTimer);
    carousel.addEventListener("mouseleave", startStoneTimer);
    carousel.addEventListener("focusin", stopStoneTimer);
    carousel.addEventListener("focusout", startStoneTimer);
    carousel.addEventListener("keydown", function (event) {
      if (event.key === "ArrowLeft") showStone(current - 1);
      if (event.key === "ArrowRight") showStone(current + 1);
    });
    startStoneTimer();
  });

  document.querySelectorAll("[data-post-carousel]").forEach(function (shell) {
    var section = shell.closest(".news-section");
    var track = shell.querySelector(".news-carousel");
    var previous = section ? section.querySelector("[data-carousel-prev]") : null;
    var next = section ? section.querySelector("[data-carousel-next]") : null;
    if (!track || !previous || !next) return;

    function carouselStep() {
      var card = track.querySelector(".news-card");
      var gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
      return card ? card.getBoundingClientRect().width + gap : track.clientWidth;
    }

    function updateCarouselControls() {
      var maximum = Math.max(0, track.scrollWidth - track.clientWidth);
      previous.disabled = track.scrollLeft <= 2;
      next.disabled = track.scrollLeft >= maximum - 2 || maximum <= 2;
    }

    previous.addEventListener("click", function () {
      track.scrollBy({ left: -carouselStep(), behavior: reduceMotion ? "auto" : "smooth" });
    });
    next.addEventListener("click", function () {
      track.scrollBy({ left: carouselStep(), behavior: reduceMotion ? "auto" : "smooth" });
    });
    track.addEventListener("scroll", updateCarouselControls, { passive: true });
    window.addEventListener("resize", updateCarouselControls);
    updateCarouselControls();
  });

  function updateHeader() {
    if (siteHeader) siteHeader.classList.toggle("is-scrolled", window.scrollY > 24);
  }

  updateHeader();
  window.addEventListener("scroll", updateHeader, { passive: true });

  if (!reduceMotion) {
    document.documentElement.classList.add("motion-ready");

    var revealGroups = [
      ".section-heading",
      ".health-heading",
      ".health-toolbar",
      ".announcement-card",
      ".weekly-word > *",
      ".health-video-card",
      ".news-card",
      ".agenda-intro",
      ".event-row",
      ".featured-person",
      ".birthday-list",
      ".history-copy",
      ".timeline",
      ".values-grid article",
      ".recognition-title",
      ".recognition-cards article",
      ".journey-heading",
      ".journey-steps article",
      ".resource-card",
      ".idea-section > *",
      ".footer-top > *"
    ];
    var revealItems = Array.from(document.querySelectorAll(revealGroups.join(",")));

    revealItems.forEach(function (item, index) {
      item.classList.add("reveal-item");
      item.style.setProperty("--reveal-delay", String((index % 4) * 70) + "ms");
    });

    if ("IntersectionObserver" in window) {
      var revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        });
      }, { rootMargin: "0px 0px -8% 0px", threshold: 0.12 });

      revealItems.forEach(function (item) { revealObserver.observe(item); });
    } else {
      revealItems.forEach(function (item) { item.classList.add("is-visible"); });
    }
  }

  document.querySelectorAll(".health-thumbnail img").forEach(function (image) {
    image.addEventListener("error", function () { image.style.display = "none"; });
  });

  var ideaForm = document.querySelector(".idea-form");
  if (ideaForm) {
    ideaForm.addEventListener("submit", function (event) {
      event.preventDefault();
      var notice = ideaForm.querySelector(".form-notice");
      if (notice) notice.textContent = "Sugestão enviada. Obrigado por contribuir!";
      ideaForm.reset();
    });
  }

  var popup = document.querySelector(".popup-backdrop");
  function closePopup() { if (popup) popup.hidden = true; }
  if (popup) {
    var title = popup.querySelector("#reflection-title");
    var reference = popup.querySelector(".reflection-reference");
    var close = popup.querySelector(".popup-close");
    var proceed = popup.querySelector(".popup-continue");
    var popupArt = popup.querySelector(".popup-art");
    var stoneName = popup.querySelector(".reflection-stone-name");
    var currentSlot = popup.getAttribute("data-reflection-slot") || ("fallback-" + new Date().toISOString().slice(0, 10));
    var endpoint = popup.getAttribute("data-reflection-endpoint") || "";
    var nextCheck = parseInt(popup.getAttribute("data-reflection-next"), 10) || 0;
    var refreshTimer;

    function reflectionStorageKey(slot) {
      return "granistone-reflection-seen:" + slot;
    }

    function reflectionWasSeen(slot) {
      try { return window.localStorage.getItem(reflectionStorageKey(slot)) === "1"; }
      catch (error) { return false; }
    }

    function markReflectionSeen(slot) {
      try { window.localStorage.setItem(reflectionStorageKey(slot), "1"); }
      catch (error) { /* O popup continua funcionando sem armazenamento local. */ }
    }

    function showReflection() {
      if (reflectionWasSeen(currentSlot)) return;
      popup.hidden = false;
      markReflectionSeen(currentSlot);
      if (close) close.focus();
    }

    function updateReflectionStone(data) {
      if (!popupArt || typeof data.stone_index === "undefined") return;
      Array.from(popupArt.classList).forEach(function (className) {
        if (/^reflection-stone-\d+$/.test(className)) popupArt.classList.remove(className);
      });
      popupArt.classList.add("reflection-stone-" + String(data.stone_index));
      popup.setAttribute("data-reflection-stone", String(data.stone_index));
      if (stoneName && data.stone_name) stoneName.textContent = data.stone_name;
    }

    function scheduleReflectionRefresh(seconds) {
      window.clearTimeout(refreshTimer);
      if (!endpoint || !seconds) return;
      refreshTimer = window.setTimeout(refreshReflection, Math.max(30, seconds) * 1000);
    }

    function refreshReflection() {
      window.fetch(endpoint, { credentials: "same-origin", cache: "no-store" })
        .then(function (response) {
          if (!response.ok) throw new Error("Não foi possível atualizar a reflexão.");
          return response.json();
        })
        .then(function (data) {
          var changed = data.slot_key && data.slot_key !== currentSlot;
          if (data.quote && title) title.textContent = data.quote;
          if (data.reference && reference) reference.textContent = data.reference;
          if (data.slot_key) currentSlot = data.slot_key;
          updateReflectionStone(data);
          scheduleReflectionRefresh(parseInt(data.seconds_until_next, 10) || 300);
          if (changed) showReflection();
        })
        .catch(function () { scheduleReflectionRefresh(300); });
    }

    if (close) close.addEventListener("click", closePopup);
    if (proceed) proceed.addEventListener("click", closePopup);
    popup.addEventListener("mousedown", function (event) { if (event.target === popup) closePopup(); });
    document.addEventListener("keydown", function (event) { if (event.key === "Escape") closePopup(); });
    window.setTimeout(showReflection, 1200);
    scheduleReflectionRefresh(nextCheck);
  }
})();
