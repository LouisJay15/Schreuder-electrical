// Aloe Credit guide — scoped to product Q&A only, talks to /chat which never
// touches account or company data (see backend/includes/guide_knowledge.php).
(function () {
  var panel = document.getElementById("chat-panel");
  if (!panel) return;

  var log = document.getElementById("chat-log");
  var form = document.getElementById("chat-form");
  var input = document.getElementById("chat-input");
  var suggestions = document.getElementById("chat-suggestions");
  var csrfInput = document.getElementById("chat-csrf");
  var sending = false;

  function addMessage(role, text) {
    var row = document.createElement("div");
    row.className = "chat-msg chat-msg-" + role;

    if (role === "agent") {
      var avatar = document.createElement("div");
      avatar.className = "chat-avatar";
      avatar.textContent = "AC";
      row.appendChild(avatar);
    }

    var bubble = document.createElement("div");
    bubble.className = "chat-bubble";
    bubble.textContent = text; // textContent only — never render user or reply text as HTML
    row.appendChild(bubble);

    log.appendChild(row);
    log.scrollTop = log.scrollHeight;
    return row;
  }

  function addCta(cta) {
    if (!cta) return;
    var link = document.createElement("a");
    link.href = cta.href;
    link.className = "btn btn-secondary chat-cta";
    link.textContent = cta.label;
    var wrap = document.createElement("div");
    wrap.className = "chat-cta-row";
    wrap.appendChild(link);
    log.appendChild(wrap);
    log.scrollTop = log.scrollHeight;
  }

  function setSuggestions(list) {
    suggestions.innerHTML = "";
    (list || []).forEach(function (q) {
      var chip = document.createElement("button");
      chip.type = "button";
      chip.className = "chat-chip";
      chip.textContent = q;
      chip.addEventListener("click", function () {
        ask(q);
      });
      suggestions.appendChild(chip);
    });
  }

  function setBusy(isBusy) {
    sending = isBusy;
    input.disabled = isBusy;
    form.querySelector("button[type=submit]").disabled = isBusy;
  }

  function ask(text) {
    if (sending || !text.trim()) return;
    addMessage("user", text);
    input.value = "";
    setBusy(true);

    var typing = addMessage("agent", "…");
    typing.classList.add("is-typing");

    fetch("/chat", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message: text, csrf_token: csrfInput.value }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        typing.remove();
        if (data && data.ok) {
          addMessage("agent", data.reply);
          addCta(data.cta);
          setSuggestions(data.suggestions);
        } else {
          addMessage("agent", (data && data.error) || "Something went wrong — please try again.");
        }
      })
      .catch(function () {
        typing.remove();
        addMessage("agent", "I couldn't reach the guide just now — please try again in a moment.");
      })
      .finally(function () {
        setBusy(false);
        input.focus();
      });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    ask(input.value);
  });

  suggestions.querySelectorAll(".chat-chip").forEach(function (chip) {
    chip.addEventListener("click", function () {
      ask(chip.getAttribute("data-q") || chip.textContent);
    });
  });
})();
