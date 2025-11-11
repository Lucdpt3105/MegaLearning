
import './bootstrap';

// Firebase Web SDK imports and initialization
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

const firebaseConfig = {
	apiKey: "AIzaSyClZXW_9cfYp79SidPh0mkmcrz4EwPvH0E",
	authDomain: "megalearning.firebaseapp.com",
	projectId: "megalearning",
	storageBucket: "megalearning.firebasestorage.app",
	messagingSenderId: "990624573767",
	appId: "1:990624573767:web:277021ae0a7d174ea0c462",
	measurementId: "G-7WBWSBX1PH"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
