import { createClient } from '@supabase/supabase-js';

// Initialize Supabase client with env variables injected via Blade
const supabaseUrl = window.__SUPABASE_URL__;
const supabaseAnonKey = window.__SUPABASE_ANON_KEY__;

let supabase = null;

if (supabaseUrl && supabaseAnonKey) {
    supabase = createClient(supabaseUrl, supabaseAnonKey);
}

/**
 * Sign in with Google using Supabase OAuth.
 * Supabase will redirect to Google, then back to our app.
 */
export async function signInWithGoogle() {
    if (!supabase) {
        console.error('Supabase client not initialized. Check your SUPABASE_URL and SUPABASE_ANON_KEY.');
        alert('Lỗi cấu hình OAuth. Vui lòng liên hệ quản trị viên.');
        return;
    }

    const { data, error } = await supabase.auth.signInWithOAuth({
        provider: 'google',
        options: {
            redirectTo: window.location.origin + '/login?supabase_callback=true',
        },
    });

    if (error) {
        console.error('Google sign-in error:', error);
        alert('Đăng nhập Google thất bại: ' + error.message);
    }
}

/**
 * Handle the OAuth callback — check if we have a session from Supabase
 * and POST the access_token to our Laravel backend.
 */
export async function handleAuthCallback() {
    if (!supabase) return;

    const { data: { session }, error } = await supabase.auth.getSession();

    if (error) {
        console.error('Error getting session:', error);
        return;
    }

    if (session && session.access_token) {
        // POST to Laravel backend
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const response = await fetch('/auth/supabase/callback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    access_token: session.access_token,
                }),
            });

            if (response.ok) {
                // Backend returns a redirect, follow it
                const result = await response.json();
                window.location.href = result.redirect || '/dashboard';
            } else if (response.redirected) {
                window.location.href = response.url;
            } else {
                // If the response is a regular page redirect (302), the browser handles it
                // For error responses, reload to show the error
                window.location.href = '/login';
            }
        } catch (err) {
            console.error('Callback error:', err);
            window.location.href = '/login';
        }
    }
}

// Auto-handle callback when page loads with supabase_callback param
if (window.location.search.includes('supabase_callback=true')) {
    // Small delay to let Supabase process the hash fragment
    setTimeout(() => {
        handleAuthCallback();
    }, 500);
}

// Expose to global scope for use in Blade templates
window.signInWithGoogle = signInWithGoogle;
