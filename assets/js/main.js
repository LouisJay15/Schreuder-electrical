// Aloe Credit — shared front-end behaviour (no session tokens ever touch this file)
(function () {
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  var toggle = document.querySelector(".nav-toggle");
  var links = document.querySelector(".nav-links");
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      var open = links.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  document.querySelectorAll("[data-accordion] .accordion-trigger").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var item = btn.closest(".accordion-item");
      var open = item.classList.toggle("is-open");
      btn.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });

  // --- Stagger index for [data-reveal] siblings, so a row of cards cascades
  // in rather than popping together --------------------------------------
  var groups = new Map();
  document.querySelectorAll("[data-reveal]").forEach(function (el) {
    var parent = el.parentElement;
    var i = groups.get(parent) || 0;
    el.style.setProperty("--reveal-i", i);
    groups.set(parent, i + 1);
  });

  if (reduceMotion) {
    document.querySelectorAll("[data-reveal]").forEach(function (el) {
      el.classList.add("is-visible");
    });
  } else if ("IntersectionObserver" in window) {
    var revealIO = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add("is-visible");
            revealIO.unobserve(e.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    document.querySelectorAll("[data-reveal]").forEach(function (el) {
      revealIO.observe(el);
    });
  } else {
    document.querySelectorAll("[data-reveal]").forEach(function (el) {
      el.classList.add("is-visible");
    });
  }

  // --- Section-to-section flow: continuous opacity/translate driven by how
  // much of the section is on screen, so motion carries across the scroll
  // instead of each block just switching on ------------------------------
  if (!reduceMotion && "IntersectionObserver" in window) {
    var flowTargets = document.querySelectorAll("main .section, main .section-tight");
    var flowIO = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          entry.target.style.setProperty("--enter", entry.intersectionRatio.toFixed(3));
        });
      },
      { threshold: buildThresholdList(20) }
    );
    flowTargets.forEach(function (el) {
      // Set the correct starting value in the same tick we add the animated
      // class, so there is never a frame where it's visible-but-unstyled.
      var rect = el.getBoundingClientRect();
      var startsOnScreen = rect.top < window.innerHeight * 0.85;
      el.style.setProperty("--enter", startsOnScreen ? "1" : "0");
      el.classList.add("js-flow");
      flowIO.observe(el);
    });
  }

  function buildThresholdList(steps) {
    var list = [];
    for (var i = 0; i <= steps; i++) list.push(i / steps);
    return list;
  }

  // --- Scroll-progress thread + header depth -----------------------------
  var progressBar = document.querySelector(".scroll-progress span");
  var header = document.querySelector(".site-header");
  var heroEl = document.querySelector(".hero");
  var heroImg = heroEl ? heroEl.querySelector(".hero-art img, .hero-art svg") : null;
  var ticking = false;

  function onScroll() {
    var doc = document.documentElement;
    var scrollTop = window.scrollY || doc.scrollTop;
    var max = doc.scrollHeight - window.innerHeight;

    if (progressBar) {
      var pct = max > 0 ? Math.min(100, (scrollTop / max) * 100) : 0;
      progressBar.style.width = pct + "%";
    }
    if (header) {
      header.classList.toggle("is-scrolled", scrollTop > 8);
    }
    if (heroImg && !reduceMotion) {
      var heroRect = heroEl.getBoundingClientRect();
      if (heroRect.bottom > 0 && heroRect.top < window.innerHeight) {
        heroImg.style.transform = "translateY(" + (scrollTop * 0.06) + "px)";
      }
    }
    ticking = false;
  }

  window.addEventListener(
    "scroll",
    function () {
      if (!ticking) {
        window.requestAnimationFrame(onScroll);
        ticking = true;
      }
    },
    { passive: true }
  );
  onScroll();

  // --- Count-up stats (e.g. hero numbers) ---------------------------------
  var counters = document.querySelectorAll("[data-count-to]");
  if (counters.length && !reduceMotion && "IntersectionObserver" in window) {
    var countIO = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          countIO.unobserve(entry.target);
          var el = entry.target;
          var target = parseFloat(el.getAttribute("data-count-to"));
          var prefix = el.getAttribute("data-count-prefix") || "";
          var suffix = el.getAttribute("data-count-suffix") || "";
          var duration = 900;
          var start = null;
          function step(ts) {
            if (start === null) start = ts;
            var progress = Math.min(1, (ts - start) / duration);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = prefix + Math.round(target * eased).toLocaleString("en-ZA") + suffix;
            if (progress < 1) window.requestAnimationFrame(step);
          }
          window.requestAnimationFrame(step);
        });
      },
      { threshold: 0.6 }
    );
    counters.forEach(function (el) {
      countIO.observe(el);
    });
  }
})();
