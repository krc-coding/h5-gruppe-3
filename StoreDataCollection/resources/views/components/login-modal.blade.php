<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Login</h2>
        <form id="loginForm">
            <label for="username">username:</label>
            <input type="username" id="loginUsername" name="username" required placeholder="Enter your username">

            <label for="password">Password:</label>
            <input type="password" id="loginPassword" name="password" required placeholder="Enter your password">

            <button type="submit" id="loginBtnSubmit" class="btn btn-primary">Login</button>
        </form>
        <button id="signUpBtn" class="btn btn-primary">Sign up</button>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("loginModal");
            let loginBtn = document.getElementById("loginBtn");
            let closeBtn = document.querySelector(".close");

            if (!modal || !loginBtn || !closeBtn) {
                console.error("Modal or buttons not found in the DOM.");
                return;
            }

            loginBtn.addEventListener("click", function() {
                modal.style.display = "flex";
                document.body.classList.add("modal-open");
            });

            closeBtn.onclick = function() {
                modal.style.display = "none";
                document.body.classList.remove("modal-open");
            };

            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                    document.body.classList.remove("modal-open");
                }
            };

            document.getElementById("loginBtnSubmit").addEventListener("click", function(event) {
                event.preventDefault();
                submitForm("/api/login");
            });

            function submitForm(apiUrl) {
                let loginForm = document.getElementById("loginForm");
                if (!loginForm.checkValidity()) {
                    loginForm.reportValidity();
                    return;
                }

                let username = document.getElementById("loginUsername").value;
                let password = document.getElementById("loginPassword").value;

                fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            username,
                            password
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data.token) {
                            localStorage.setItem("authToken", data.data.token);
                            modal.style.display = "none";

                        } else {
                            alert(data.message || "login failed.");
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
