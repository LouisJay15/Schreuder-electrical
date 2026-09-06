(function () {
  var form = document.getElementById("calc-form");
  if (!form) return;

  var amountInput = document.getElementById("calc-amount");
  var termInput = document.getElementById("calc-term");
  var rateInput = document.getElementById("calc-rate");
  var amountLabel = document.getElementById("calc-amount-label");
  var termLabel = document.getElementById("calc-term-label");
  var rateLabel = document.getElementById("calc-rate-label");

  var monthlyOut = document.getElementById("calc-monthly");
  var totalOut = document.getElementById("calc-total");
  var interestOut = document.getElementById("calc-interest");
  var scheduleBody = document.getElementById("calc-schedule-body");

  function money(n) {
    return "R" + n.toLocaleString("en-ZA", { maximumFractionDigits: 0 });
  }

  function compute() {
    var principal = parseFloat(amountInput.value) || 0;
    var months = parseInt(termInput.value, 10) || 1;
    var annualRate = parseFloat(rateInput.value) || 0;
    var monthlyRate = annualRate / 100 / 12;

    var payment;
    if (monthlyRate === 0) {
      payment = principal / months;
    } else {
      var factor = Math.pow(1 + monthlyRate, months);
      payment = (principal * monthlyRate * factor) / (factor - 1);
    }

    var totalRepayment = payment * months;
    var totalInterest = totalRepayment - principal;

    amountLabel.textContent = money(principal);
    termLabel.textContent = months + " months";
    rateLabel.textContent = annualRate.toFixed(1) + "% p.a.";
    monthlyOut.textContent = money(payment);
    totalOut.textContent = money(totalRepayment);
    interestOut.textContent = money(totalInterest);

    scheduleBody.innerHTML = "";
    var balance = principal;
    var rowsToShow = Math.min(months, 12);
    for (var i = 1; i <= rowsToShow; i++) {
      var interestPortion = balance * monthlyRate;
      var principalPortion = payment - interestPortion;
      balance = Math.max(0, balance - principalPortion);
      var tr = document.createElement("tr");
      tr.innerHTML =
        "<td>" + i + "</td><td>" + money(payment) + "</td><td>" +
        money(interestPortion) + "</td><td>" + money(principalPortion) +
        "</td><td>" + money(balance) + "</td>";
      scheduleBody.appendChild(tr);
    }
    if (months > 12) {
      var note = document.createElement("tr");
      note.innerHTML = '<td colspan="5" class="muted">…continues for ' + (months - 12) + ' more months</td>';
      scheduleBody.appendChild(note);
    }
  }

  ["input", "change"].forEach(function (evt) {
    amountInput.addEventListener(evt, compute);
    termInput.addEventListener(evt, compute);
    rateInput.addEventListener(evt, compute);
  });

  compute();
})();
