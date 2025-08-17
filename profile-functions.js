// Shared Profile Functions for Anihan System

// Initialize Supabase client (shared globally)
let supabaseClient;

// Function to initialize Supabase if not already done
function initializeSupabase() {
    if (!supabaseClient) {
        const SUPABASE_URL = 'https://ontuivohwjfkxjwrjnot.supabase.co';
        const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9udHVpdm9od2pma3hqd3Jqbm90Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTQ2Njg3MTAsImV4cCI6MjA3MDI0NDcxMH0.jGQhshEtfnABK8xNF98WxB10c66vIkTzAoLrhxbeQwE';
        supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
    }
    return supabaseClient;
}

// Function to load current user profile data
async function loadCurrentUserProfile() {
    try {
        const client = initializeSupabase();
        // Get current user from session storage
        const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        
        if (!currentUser.id) {
            // If no user session, redirect to login
            console.log('No user session found, redirecting to login');
            window.location.href = 'sign_in_form.html';
            return;
        }
        
        console.log('Loading profile for user:', currentUser);
        
        // Fetch latest user data from database
        const { data: userData, error } = await client
            .from('admin_accounts')
            .select('*')
            .eq('id', currentUser.id)
            .single();
        
        if (error) throw error;
        
        console.log('User data loaded:', userData);
        
        // Update profile display
        updateProfileDisplay(userData);
        
    } catch (error) {
        console.error('Error loading user profile:', error);
        // Fallback to session data if database fetch fails
        const sessionUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        if (sessionUser.name) {
            console.log('Using session data fallback:', sessionUser);
            updateProfileDisplay(sessionUser);
        }
    }
}
if (typeof supabaseClient === 'undefined') {
    const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
}

// Function to load current user profile data
async function loadCurrentUserProfile() {
    try {
        // Get current user from session storage
        const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        
        if (!currentUser.id) {
            // If no user session, redirect to login
            window.location.href = 'sign_in_form.html';
            return;
        }
        
        // Fetch latest user data from database
        const { data: userData, error } = await supabaseClient
            .from('admin_accounts')
            .select('*')
            .eq('id', currentUser.id)
            .single();
        
        if (error) throw error;
        
        // Update profile display
        updateProfileDisplay(userData);
        
    } catch (error) {
        console.error('Error loading user profile:', error);
        // Fallback to session data if database fetch fails
        const sessionUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        if (sessionUser.name) {
            updateProfileDisplay(sessionUser);
        }
    }
}

// Function to update profile display elements
function updateProfileDisplay(userData) {
    // Update profile name
    const profileName = document.getElementById('profileUserName');
    if (profileName) {
        profileName.textContent = userData.name || 'Administrator';
    }
    
    // Update profile email
    const profileEmail = document.getElementById('profileUserEmail');
    if (profileEmail) {
        profileEmail.textContent = userData.email || 'admin@anihan.gov.ph';
    }
    
    // Update profile role
    const profileRole = document.getElementById('profileUserRole');
    if (profileRole) {
        profileRole.textContent = userData.user_type || userData.userType || 'Admin';
    }
    
    // Update profile title based on role
    const profileTitle = document.getElementById('profileUserTitle');
    if (profileTitle) {
        if ((userData.user_type || userData.userType) === 'SUPER ADMIN') {
            profileTitle.textContent = 'System Administrator';
        } else {
            profileTitle.textContent = 'Municipal Administrator';
        }
    }
    
    // Update profile images if available
    if (userData.image_url) {
        // Update main avatar
        const userAvatarImage = document.getElementById('userAvatarImage');
        const userAvatarIcon = document.getElementById('userAvatarIcon');
        if (userAvatarImage && userAvatarIcon) {
            userAvatarImage.src = userData.image_url;
            userAvatarImage.style.display = 'block';
            userAvatarIcon.style.display = 'none';
        }
        
        // Update dropdown avatar
        const profileAvatarImage = document.getElementById('profileAvatarImage');
        const profileAvatarIcon = document.getElementById('profileAvatarIcon');
        if (profileAvatarImage && profileAvatarIcon) {
            profileAvatarImage.src = userData.image_url;
            profileAvatarImage.style.display = 'block';
            profileAvatarIcon.style.display = 'none';
        }
    }
}

// Function to check user authentication
function checkUserAuthentication() {
    const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
    if (!currentUser.id) {
        window.location.href = 'sign_in_form.html';
        return false;
    }
    return true;
}

// Function to get current user data
function getCurrentUser() {
    return JSON.parse(sessionStorage.getItem('currentUser') || '{}');
}

// Function to toggle profile dropdown (shared across pages)
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Function to sign out
function signOut() {
    if (confirm('Are you sure you want to sign out?')) {
        sessionStorage.removeItem('currentUser');
        window.location.href = 'sign_in_form.html';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const userAvatar = document.querySelector('.user-avatar');
    
    if (dropdown && userAvatar && !userAvatar.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Auto-load profile when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only load profile if we're not on the sign-in page
    if (!window.location.pathname.includes('sign_in_form.html')) {
        loadCurrentUserProfile();
    }
});
