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
    let username = null;
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
                    console.log("Account data not found. Creating from scratch");

                    // Check if account data exists
                    const docRef = doc(db, "user", user.uid);
                    getDoc(docRef).then((docSnap) => {
                        if (docSnap.exists()) {
                            console.log("Account data already exist!");
                        } else {
                            let accName = null;
                            if (username) accName = username;
                            else if (user.displayName) accName = user.displayName;
                            else accName = user.email.split("@")[0];

                            // Initialise account data
                            setDoc(doc(userRef, user.uid), {
                                username: accName,
                                favourite: [],
                                like: []
                            }).then(() => {
                                alert("Account data not found! Data recreated from scratch");
                                window.location.href = '../index.php';
                            });
                        }
                    });
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
        username = document.getElementById("register-username").value;
        let email = document.getElementById("register-email").value;
        let password = document.getElementById("register-password").value;
        let reenterpass = document.getElementById("register-reenterpass").value;

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
        let email = document.getElementById("login-email").value;
        let password = document.getElementById("login-password").value;

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