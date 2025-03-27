<div id="signUpModal" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Sign Up</h2>
        <form id="signUpForm">
            <label for="username">Username:</label>
            <input type="username" id="username" name="username" required placeholder="Enter your username">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">

            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                placeholder="Confirm your password">

            <button type="submit" id="signUpBtnSubmit" class="btn btn-primary">Sign up</button>
        </form>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let signUpModal = document.getElementById("signUpModal");
            let signUpBtn = document.getElementById("signUpBtn");
            let closeBtns = document.querySelectorAll(".close");

            if (!signUpModal || !signUpBtn) {
                console.error("Sign-Up Modal or button not found.");
                return;
            }

            signUpBtn.addEventListener("click", function() {
                signUpModal.style.display = "flex";
                loginModal.style.display = "none";
                document.body.classList.add("modal-open");
            });

            closeBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    signUpModal.style.display = "none";
                    document.body.classList.remove("modal-open");
                });
            });

            window.onclick = function(event) {
                if (event.target === signUpModal) {
                    signUpModal.style.display = "none";
                    document.body.classList.remove("modal-open");
                }
            };

            document.getElementById("signUpBtnSubmit").addEventListener("click", function(event) {
                event.preventDefault();
                submitSignUpForm("/api/user");
            });

            function submitSignUpForm(apiUrl) {
                let signUpForm = document.getElementById("signUpForm");
                if (!signUpForm.checkValidity()) {
                    signUpForm.reportValidity();
                    return;
                }

                let username = document.getElementById("username").value;
                let password = document.getElementById("password").value;
                let password_confirmation = document.getElementById("password_confirmation").value;

                if (password !== password_confirmation) {
                    alert("Passwords do not match!");
                    return;
                }

                fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            username,
                            password,
                            password_confirmation
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data.token) {
                            localStorage.setItem("authToken", data.data.token);

                            signUpModal.style.display = "none";
                            document.body.classList.remove("modal-open");
                        } else {
                            alert(data.message || "Sign-up failed.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred. Please try again.");
                    });
            }
        });
    </script>
</div>
