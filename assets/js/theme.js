(function () {
  "use strict";

  var menuButton = document.querySelector(".menu-button");
  var mainNav = document.querySelector(".main-nav");
  var siteHeader = document.querySelector(".site-header");
  var navSections = [];
  if (menuButton && mainNav) {
    function setMenu(open) {
      mainNav.classList.toggle("open", open);
      menuButton.setAttribute("aria-expanded", String(open));
    }

    menuButton.addEventListener("click", function () {
      setMenu(!mainNav.classList.contains("open"));
    });

    mainNav.querySelectorAll("a[href]").forEach(function (link) {
      var url = new URL(link.href, window.location.href);
      var target = url.origin === window.location.origin && url.pathname === window.location.pathname
        && url.hash && document.getElementById(url.hash.slice(1));
      if (target && document.getElementById("inicio")) navSections.push({ link: link, target: target });

      link.addEventListener("click", function (event) {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        setMenu(false);
        if (target) {
          target.setAttribute("tabindex", "-1");
          target.focus({ preventScroll: true });
        }
      });
    });

    siteHeader.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && mainNav.classList.contains("open")) {
        setMenu(false);
        menuButton.focus();
      }
    });
    siteHeader.addEventListener("focusout", function (event) {
      if (event.relatedTarget && !siteHeader.contains(event.relatedTarget)) setMenu(false);
    });
  }

  function bindFilter(buttonSelector, itemSelector, buttonAttribute, itemAttribute, counterSelector) {
    var buttons = Array.from(document.querySelectorAll(buttonSelector));
    var items = Array.from(document.querySelectorAll(itemSelector));
    var counter = document.querySelector(counterSelector);
    buttons.forEach(function (button) {
      button.setAttribute("aria-pressed", String(button.classList.contains("active")));
      button.addEventListener("click", function () {
        var filter = button.getAttribute(buttonAttribute);
        var visible = 0;
        buttons.forEach(function (candidate) {
          var active = candidate === button;
          candidate.classList.toggle("active", active);
          candidate.setAttribute("aria-pressed", String(active));
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

  document.querySelectorAll("[data-stone-carousel]").forEach(function (carousel) {
    var slides = Array.from(carousel.querySelectorAll("[data-stone-slide]"));
    var dots = Array.from(carousel.querySelectorAll("[data-stone-dot]"));
    var dotsGroup = carousel.querySelector(".stone-dots");
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
        var active = dotIndex === current;
        dot.classList.toggle("is-active", active);
        dot.setAttribute("aria-pressed", String(active));
        if (active && dotsGroup) {
          if (dot.offsetTop < dotsGroup.scrollTop) dotsGroup.scrollTop = dot.offsetTop;
          if (dot.offsetTop + dot.offsetHeight > dotsGroup.scrollTop + dotsGroup.clientHeight) {
            dotsGroup.scrollTop = dot.offsetTop + dot.offsetHeight - dotsGroup.clientHeight;
          }
        }
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
      if (!reduceMotion && !carousel.matches(":hover, :focus-within")) {
        timer = window.setInterval(function () { showStone(nextRandomStone()); }, 5000);
      }
    }

    if (previous) previous.addEventListener("click", function () { showStone(current - 1); startStoneTimer(); });
    if (next) next.addEventListener("click", function () { showStone(current + 1); startStoneTimer(); });
    dots.forEach(function (dot, index) {
      dot.addEventListener("click", function () { showStone(index); startStoneTimer(); });
    });
    carousel.addEventListener("mouseenter", stopStoneTimer);
    carousel.addEventListener("mouseleave", startStoneTimer);
    carousel.addEventListener("focusin", stopStoneTimer);
    carousel.addEventListener("focusout", function () { window.requestAnimationFrame(startStoneTimer); });
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
      previous.parentElement.hidden = maximum <= 2;
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

  var headerFrame = 0;
  var headerOffset = 0;
  var activeNav = null;
  function updateHeader() {
    headerFrame = 0;
    if (!siteHeader) return;
    siteHeader.classList.toggle("is-scrolled", window.scrollY > 24);
    var offset = Math.max(0, Math.ceil(siteHeader.getBoundingClientRect().bottom));
    if (offset !== headerOffset) {
      headerOffset = offset;
      document.documentElement.style.setProperty("--portal-header-offset", offset + "px");
    }

    if (!navSections.length) return;
    var active = navSections[0];
    var nearest = -Infinity;
    navSections.forEach(function (entry) {
      var top = entry.target.getBoundingClientRect().top;
      if (top <= offset + 40 && top > nearest) {
        active = entry;
        nearest = top;
      }
    });
    if (window.scrollY > 0 && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 4) {
      active = navSections[navSections.length - 1];
    }
    if (activeNav !== active) {
      if (activeNav) activeNav.link.removeAttribute("aria-current");
      active.link.setAttribute("aria-current", "location");
      activeNav = active;
    }
  }

  function scheduleHeaderUpdate() {
    if (!headerFrame) headerFrame = window.requestAnimationFrame(updateHeader);
  }
  updateHeader();
  window.addEventListener("scroll", scheduleHeaderUpdate, { passive: true });
  window.addEventListener("resize", scheduleHeaderUpdate);
  window.addEventListener("load", scheduleHeaderUpdate);

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
    function showFallback() { image.hidden = true; }
    image.addEventListener("error", showFallback);
    if (image.complete && !image.naturalWidth) showFallback();
  });

  var videoDialog = document.querySelector("#portal-video-dialog");
  if (videoDialog && typeof videoDialog.showModal === "function") {
    var videoMedia = videoDialog.querySelector("[data-video-media]");
    var videoTitle = videoDialog.querySelector("#portal-video-title");
    var videoDescription = videoDialog.querySelector("[data-video-description]");
    var videoError = videoDialog.querySelector(".portal-video-error");
    var videoTrigger;

    document.querySelector(".health-video-grid").addEventListener("click", function (event) {
      var trigger = event.target.closest("[data-video-open]");
      if (!trigger || videoDialog.open) return;

      var card = trigger.closest("[data-video-src]");
      var description = card.querySelector(".health-video-copy > p");
      var isEmbed = card.dataset.videoType === "iframe";
      var player = document.createElement(isEmbed ? "iframe" : "video");
      videoTrigger = trigger;
      videoTitle.textContent = card.querySelector("h3").textContent.trim();
      videoDescription.textContent = description ? description.textContent.trim() : "";
      videoDescription.hidden = !videoDescription.textContent;
      videoError.hidden = true;

      if (isEmbed) {
        player.title = videoTitle.textContent;
        player.allow = "autoplay; encrypted-media; picture-in-picture; fullscreen";
        player.allowFullscreen = true;
        player.referrerPolicy = "strict-origin-when-cross-origin";
      } else {
        player.controls = true;
        player.playsInline = true;
        player.preload = "metadata";
        player.addEventListener("error", function () {
          if (player.isConnected) videoError.hidden = false;
        });
      }

      player.src = card.dataset.videoSrc;
      videoMedia.appendChild(player);
      videoDialog.showModal();
      if (!isEmbed) player.play().catch(function () { /* Os controles ficam disponíveis. */ });
    });

    videoDialog.querySelector("[data-video-close]").addEventListener("click", function () {
      videoDialog.close();
    });
    videoDialog.addEventListener("close", function () {
      var player = videoMedia.firstElementChild;
      if (player && player.tagName === "VIDEO") {
        player.pause();
        player.removeAttribute("src");
        player.load();
      }
      videoMedia.textContent = "";
      if (videoTrigger) videoTrigger.focus({ preventScroll: true });
    });
  }

  var qrFigure = document.querySelector("[data-reporting-qr]");
  var reportingLink = document.querySelector(".reporting-link");
  if (qrFigure && reportingLink && typeof window.qrcode === "function") {
    var canvas = qrFigure.querySelector("canvas");
    try {
      var qr = window.qrcode(0, "M");
      qr.addData(reportingLink.href);
      qr.make();
      var modules = qr.getModuleCount();
      var quietZone = 4;
      var scale = 5;
      var side = (modules + quietZone * 2) * scale;
      canvas.width = canvas.height = side;
      var context = canvas.getContext("2d");
      context.fillStyle = "#fff";
      context.fillRect(0, 0, side, side);
      context.fillStyle = "#111";
      for (var row = 0; row < modules; row += 1) {
        for (var column = 0; column < modules; column += 1) {
          if (qr.isDark(row, column)) context.fillRect((column + quietZone) * scale, (row + quietZone) * scale, scale, scale);
        }
      }
      qrFigure.hidden = false;
    } catch (error) {
      // Mantém o acesso direto disponível quando o código não pode ser gerado.
    }
  }

  var ideaForm = document.querySelector(".idea-form");
  if (ideaForm && ideaForm.querySelector("button[type='submit']:disabled")) {
    ideaForm.addEventListener("submit", function (event) { event.preventDefault(); });
  }

  var popup = document.querySelector(".popup-backdrop");
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
    var focusBeforePopup;

    function closePopup() {
      if (popup.hidden) return;
      popup.hidden = true;
      if (focusBeforePopup && focusBeforePopup !== document.body) focusBeforePopup.focus({ preventScroll: true });
    }

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
      if ((videoDialog && videoDialog.open) || reflectionWasSeen(currentSlot)) return;
      if (popup.hidden) focusBeforePopup = document.activeElement;
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
    document.addEventListener("keydown", function (event) {
      if (popup.hidden) return;
      if (event.key === "Escape") {
        closePopup();
      } else if (event.key === "Tab" && close && proceed) {
        var boundary = event.shiftKey ? close : proceed;
        if (!popup.contains(document.activeElement) || document.activeElement === boundary) {
          event.preventDefault();
          (event.shiftKey ? proceed : close).focus();
        }
      }
    });
    window.setTimeout(showReflection, 1200);
    scheduleReflectionRefresh(nextCheck);
  }
})();
