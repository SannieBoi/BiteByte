const form            = document.getElementById("registration-form");
const username        = document.getElementById("username");
const email           = document.getElementById("email");
const password        = document.getElementById("password");
const confirmPassword = document.getElementById("confirm-password");

const passwordRules = [
    { id: "rule-length",  test: v => v.length >= 8,           label: "At least 8 characters"             },
    { id: "rule-upper",   test: v => /[A-Z]/.test(v),         label: "One uppercase letter (A–Z)"         },
    { id: "rule-lower",   test: v => /[a-z]/.test(v),         label: "One lowercase letter (a–z)"         },
    { id: "rule-number",  test: v => /[0-9]/.test(v),         label: "One number (0–9)"                   },
    { id: "rule-symbol",  test: v => /[^A-Za-z0-9]/.test(v), label: "One symbol (!@#$...)"               },
];

function createRulesBox(inputEl, includeStrengthRules = true) {
    const box = document.createElement("div");
    box.className = "password-rules";

    const inner = document.createElement("div");
    inner.className = "password-rules-inner";

    if (includeStrengthRules) {
        inner.innerHTML = passwordRules.map(r =>
            `<p class="rule" id="${r.id}-${inputEl.id}">
                <i class="fas fa-circle-xmark rule-icon" aria-hidden="true"></i> ${r.label}
            </p>`
        ).join("");
    } else {
        inner.innerHTML = `<p class="rule rule-match">
            <i class="fas fa-circle-xmark rule-icon" aria-hidden="true"></i> Passwords match
        </p>`;
    }

    box.appendChild(inner);

    const formGroup = inputEl.closest(".form-group");
    const small = formGroup.querySelector("small");
    small.insertAdjacentElement("afterend", box);
    return box;
}

const passwordRulesBox = createRulesBox(password);
const confirmRulesBox  = createRulesBox(confirmPassword, false);

password.addEventListener("focus",        () => passwordRulesBox.classList.add("visible"));
password.addEventListener("blur",         () => passwordRulesBox.classList.remove("visible"));
confirmPassword.addEventListener("focus", () => confirmRulesBox.classList.add("visible"));
confirmPassword.addEventListener("blur",  () => confirmRulesBox.classList.remove("visible"));

email.addEventListener("input", () => {
    if (email.value.trim() === "") {
        showError(email, "Email is required");
    } else {
        checkEmail(email);
    }
});

username.addEventListener("input", () => {
    if (username.value.trim() === "") {
        showError(username, "Username is required");
    } else {
        checkLength(username, 3, 15);
    }
});

password.addEventListener("input", () => {
    updateRules(password.value, password.id);
    if (confirmPassword.value.trim() !== "") liveCheckMatch();
});

confirmPassword.addEventListener("input", liveCheckMatch);

function updateRules(value, inputId) {
    passwordRules.forEach(rule => {
        const el   = document.getElementById(`${rule.id}-${inputId}`);
        const icon = el.querySelector(".rule-icon");
        const pass = rule.test(value);

        el.classList.toggle("passed", pass);
        icon.classList.toggle("fa-circle-check", pass);
        icon.classList.toggle("fa-circle-xmark", !pass);
    });
}

function liveCheckMatch() {
    const matchRule = confirmRulesBox.querySelector(".rule-match");
    const icon      = matchRule.querySelector(".rule-icon");
    const matches   = password.value === confirmPassword.value && confirmPassword.value !== "";

    matchRule.classList.toggle("passed", matches);
    icon.classList.toggle("fa-circle-check", matches);
    icon.classList.toggle("fa-circle-xmark", !matches);
}

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const isRequiredValid = checkRequired([username, email, password, confirmPassword]);
    let isUsernameValid   = true;
    let isEmailValid      =      true;
    let isPasswordValid   = true;
    let isPasswordsMatch  = true;

    if (username.value.trim() !== "")
        isUsernameValid = checkLength(username, 3, 15);

    if (email.value.trim() !== "")
        isEmailValid = checkEmail(email);

    if (password.value.trim() !== "")
        isPasswordValid = checkPasswordStrength(password);

    if (password.value.trim() !== "" && confirmPassword.value.trim() !== "")
        isPasswordsMatch = checkPasswordsMatch(password, confirmPassword);

    if (isRequiredValid && isUsernameValid && isEmailValid && isPasswordValid && isPasswordsMatch) {
        e.currentTarget.submit();
    }
});

function checkRequired(inputArr) {
    let isValid = true;
    inputArr.forEach(input => {
        if (input.value.trim() === "") {
            showError(input, `${formatFieldName(input)} is required`);
            isValid = false;
        }
    });
    return isValid;
}

function formatFieldName(input) {
    return input.id
        .split("-")
        .map(w => w.charAt(0).toUpperCase() + w.slice(1))
        .join(" ");
}

function showError(input, message) {
    const formGroup = input.closest(".form-group");
    formGroup.className = "form-group error";
    const small = formGroup.querySelector("small");
    if (small) small.innerText = message;
}

function showSuccess(input) {
    const formGroup = input.closest(".form-group");
    formGroup.className = "form-group success";
    const small = formGroup.querySelector("small");
    if (small) small.innerText = "";
}

function checkLength(input, min, max) {
    if (input.value.length < min) {
        showError(input, `${formatFieldName(input)} must be at least ${min} characters`);
        return false;
    } else if (input.value.length > max) {
        showError(input, `${formatFieldName(input)} must be less than ${max} characters`);
        return false;
    }
    showSuccess(input);
    return true;
}

function checkEmail(emailInput) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(emailInput.value.trim())) {
        showSuccess(emailInput);
        return true;
    }
    showError(emailInput, "Email is not valid");
    return false;
}

function checkPasswordStrength(input) {
    const allPass = passwordRules.every(r => r.test(input.value));
    if (allPass) {
        showSuccess(input);
        return true;
    }
    showError(input, "Password doesn't meet all requirements");
    return false;
}

function checkPasswordsMatch(pw, confirm) {
    if (pw.value !== confirm.value) {
        showError(confirm, "Passwords do not match");
        return false;
    }
    showSuccess(confirm);
    return true;
}