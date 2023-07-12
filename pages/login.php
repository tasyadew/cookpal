<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookPAL</title>
    <link rel="icon" href="../assets/cookpal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="login-bg">
    <div class="form-box">
        <img src="../assets/cookpal.png">
        <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="loginBtn()">Log In</button>
            <button type="button" class="toggle-btn" onclick="registerBtn()">Register</button>
        </div>
        <div class="social-icons">
            <button id="googleLogin" class="google-btn">
                <i class="fa-brands fa-google"></i>Continue with Google
            </button>
            <div>OR</div>
        </div>

        <!-- Login Form -->
        <form id="login-form" name="login-form" class="input-group" method="post" action="#"
            enctype="multipart/form-data">
            <input type="email" id="login-email" name="login-email" class="input-field" placeholder="E-mail" autocomplete="email"
                required>
            <input type="password" id="login-password" name="login-password" class="input-field" placeholder="Enter Password" autocomplete="current-password"
                required>
            <input type="checkbox" name="checkbox" class="check-box" onclick="showPass(this, 'login-password', null)"><span>Show Password</span>
            <button type="button" id="loginBtn" name="loginBtn" class="submit-btn">Log In</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" name="register-form" class="input-group" method="post" action="#"
            enctype="multipart/form-data">
            <input type="text" id="register-username" name="register-username" class="input-field" placeholder="Username" autocomplete="username"
                required>
            <input type="email" id="register-email" name="register-email" class="input-field" placeholder="E-mail" autocomplete="email"
                required>
            <input type="password" id="register-password" name="register-password" class="input-field" placeholder="Enter Password" autocomplete="new-password"
                required>
            <input type="password" id="register-reenterpass" name="register-reenterpass" class="input-field" placeholder="Reenter Password" autocomplete="new-password"
                required>
            <input type="checkbox" name="checkbox" class="check-box" onclick="showPass(this, 'register-password', 'register-reenterpass')"><span>Show Password</span>
            <button type="button" id="registerBtn" name="registerBtn" class="submit-btn">Register</button>
        </form>
    </div>
</body>

<script src="../js/login.js"></script>

<!-- Firebase Auth Module -->
<script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, GoogleAuthProvider, signInWithPopup, createUserWithEmailAndPassword, signInWithEmailAndPassword }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-auth.js";
    import { getFirestore, collection, doc, getDoc, setDoc }
        from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyDDH3bFZb8rIP9l1sQ8aBZHCQbhTgn3wOk",
        authDomain: "cookpal-a47f7.firebaseapp.com",
        projectId: "cookpal-a47f7",
        storageBucket: "cookpal-a47f7.appspot.com",
        messagingSenderId: "568342145857",
        appId: "1:568342145857:web:00a747e32b4c59746b8fc0"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth();
    const provider = new GoogleAuthProvider(app);
    const db = getFirestore(app);
    const userRef = collection(db, "user");
    console.log(app);
    onAuthStateChanged(auth, (user) => {
        // Check if user is signed in
        if (user) {
            // Check if account data exists
            const docRef = doc(db, "user", user.uid);
            getDoc(docRef).then((docSnap)=>{
                if (docSnap.exists()) {
                    console.log("Account data exist!");
                    window.location.href = '../index.php';
                } else {
                    console.log("Account data not found");
                }
            });
        }
    });

    //----- Continue with Google
    document.getElementById("googleLogin").addEventListener('click', () => {
        signInWithPopup(auth, provider)
            .then((result) => {
                // This gives you a Google Access Token. You can use it to access the Google API.
                const credential = GoogleAuthProvider.credentialFromResult(result);
                const token = credential.accessToken;

                // The signed-in user info.
                const user = result.user;

                // Check if account data exists
                const docRef = doc(db, "user", user.uid);
                getDoc(docRef).then((docSnap) => {
                    if (docSnap.exists()) {
                        console.log("Account data already exist!");
                    } else {
                        // Initialise account data
                        setDoc(doc(userRef, user.uid), {
                            username: user.displayName,
                            favourite: [],
                            like: []
                        }).then(() => {
                            alert("Signed in successfully!!");
                            window.location.href = '../index.php';
                        });
                    }
                });
            }).catch((error) => {
                alert(error.message);
            });
            
    });

    //----- New Registration code start	  
    document.getElementById("registerBtn").addEventListener("click", () => {
        var username = document.getElementById("register-username").value;
        var email = document.getElementById("register-email").value;
        var password = document.getElementById("register-password").value;
        var reenterpass = document.getElementById("register-reenterpass").value;

        // Check if password matches
        if (password != reenterpass) {
            alert("Password does not match!!");
            return; // Prevent user from register
        }

        // Create a new account
        createUserWithEmailAndPassword(auth, email, password)
            .then((userCredential) => {
                // Signed in 
                const user = userCredential.user;

                // Initialise account data
                setDoc(doc(userRef, user.uid), {
                    username: username,
                    favourite: [],
                    like: []
                }).then(()=>{
                    console.log(user);
                    alert("Registration successfully!!");
                    window.location.href = '../index.php';
                });
            })
            .catch((error) => {
                const errorCode = error.code;
                const errorMessage = error.message;
                
                console.log(errorMessage);
                alert(error);
            });
    });
    //----- End

    //----- Login code start	  
    document.getElementById("loginBtn").addEventListener("click", () => {
        var email = document.getElementById("login-email").value;
        var password = document.getElementById("login-password").value;

        signInWithEmailAndPassword(auth, email, password)
            .then((userCredential) => {
                // Signed in 
                const user = userCredential.user;
                console.log(user);

                alert("Login successfully!!");
            })
            .catch((error) => {
                const errorCode = error.code;
                const errorMessage = error.message;
                console.log(errorMessage);
                alert(errorMessage);
            });
    });
    //----- End
</script>

</html>