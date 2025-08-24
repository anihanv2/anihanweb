
// Supabase configuration
const SUPABASE_URL = 'https://ontuivohwjfkxjwrjnot.supabase.co'
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9udHVpdm9od2pma3hqd3Jqbm90Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTQ2Njg3MTAsImV4cCI6MjA3MDI0NDcxMH0.jGQhshEtfnABK8xNF98WxB10c66vIkTzAoLrhxbeQwE'

// Initialize Supabase client
let supabaseClient;
try {
    supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
} catch (error) {
    console.error('Failed to initialize Supabase client:', error);
}

// Current user ID for tracking
window.currentUserId = null;

// Initialize profile when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Shared profile system initialized');
    
    // Small delay to ensure all elements are loaded
    setTimeout(() => {
        loadCurrentUserProfile();
    }, 100);
});

// Profile loading functions
async function loadCurrentUserProfile() {
    try {
        console.log('=== LOADING CURRENT USER PROFILE ===');
        
        // Get current user from session storage (set during login)
        const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        console.log('Session storage user:', currentUser);
        
        if (!currentUser.id || currentUser.id === 'fallback') {
            console.log('No valid logged-in user found in session, checking database...');
            await loadFirstUserForTesting();
            return;
        }
        
        console.log('Found logged-in user with ID:', currentUser.id);
        window.currentUserId = currentUser.id;
        
        // Fetch the actual user data from database using their ID
        try {
            if (!supabaseClient) {
                console.error('Supabase client not available');
                await loadFirstUserForTesting();
                return;
            }
            
            const { data: userData, error } = await supabaseClient
                .from('admin_accounts')
                .select('*')
                .eq('id', currentUser.id)
                .single();
            
            if (error) {
                console.error('Database error fetching user:', error);
                await loadFirstUserForTesting();
                return;
            }
            
            if (userData) {
                console.log('Logged-in user data from database:', userData);
                
                // Update session with fresh data
                const userSession = {
                    id: userData.id,
                    name: userData.name,
                    email: userData.email,
                    userType: userData.user_type,
                    image_url: userData.image_url,
                    address: userData.address,
                    municipality: userData.municipality
                };
                sessionStorage.setItem('currentUser', JSON.stringify(userSession));
                
                // Update profile display with logged-in user's data
                updateProfileDisplay({
                    name: userData.name,
                    email: userData.email,
                    user_type: userData.user_type,
                    image_url: userData.image_url,
                    address: userData.address,
                    municipality: userData.municipality
                });
            } else {
                console.log('No user data found for ID:', currentUser.id);
                updateProfileDisplay(currentUser);
            }
            
        } catch (dbError) {
            console.error('Database connection error:', dbError);
            await loadFirstUserForTesting();
        }
        
    } catch (error) {
        console.error('Error in loadCurrentUserProfile:', error);
        await loadFirstUserForTesting();
    }
}

// Fallback function to load any user for testing
async function loadFirstUserForTesting() {
    try {
        console.log('=== LOADING FIRST USER FOR TESTING ===');
        
        if (!supabaseClient) {
            console.error('Supabase client not available, using fallback data');
            updateProfileDisplay({
                name: 'Administrator',
                email: 'admin@anihan.gov.ph',
                user_type: 'SUPER ADMIN',
                image_url: null,
                address: null,
                municipality: null
            });
            return;
        }
        
        const { data: users, error } = await supabaseClient
            .from('admin_accounts')
            .select('*')
            .limit(1);
        
        if (error) {
            console.error('Database error in loadFirstUserForTesting:', error);
            updateProfileDisplay({
                name: 'Administrator',
                email: 'admin@anihan.gov.ph',
                user_type: 'SUPER ADMIN',
                image_url: null,
                address: null,
                municipality: null
            });
            return;
        }
        
        if (users && users.length > 0) {
            const user = users[0];
            console.log('Using first user from database:', user);
            
            // Store user info in session for municipality dashboard
            const userSession = {
                id: user.id,
                name: user.name,
                email: user.email,
                userType: user.user_type,
                image_url: user.image_url,
                address: user.address,
                municipality: user.municipality
            };
            sessionStorage.setItem('currentUser', JSON.stringify(userSession));
            
            updateProfileDisplay({
                name: user.name,
                email: user.email,
                user_type: user.user_type,
                image_url: user.image_url,
                address: user.address,
                municipality: user.municipality
            });
        } else {
            console.log('No users in database, using default');
            updateProfileDisplay({
                name: 'Administrator',
                email: 'admin@anihan.gov.ph',
                user_type: 'SUPER ADMIN',
                image_url: null,
                address: null,
                municipality: null
            });
        }
        
    } catch (error) {
        console.error('Error in loadFirstUserForTesting:', error);
        updateProfileDisplay({
            name: 'Administrator',
            email: 'admin@anihan.gov.ph',
            user_type: 'SUPER ADMIN',
            image_url: null,
            address: null,
            municipality: null
        });
    }
}

function updateProfileDisplay(userData) {
    console.log('=== UPDATING PROFILE DISPLAY ===');
    console.log('Updating profile display with data:', userData);
    
    // Update profile name
    const profileName = document.getElementById('profileUserName');
    if (profileName) {
        const displayName = userData.name || 'Administrator';
        profileName.textContent = displayName;
        console.log('✅ Updated profile name to:', displayName);
    } else {
        console.log('❌ Profile name element not found');
    }
    
    // Update profile email
    const profileEmail = document.getElementById('profileUserEmail');
    if (profileEmail) {
        const displayEmail = userData.email || 'admin@anihan.gov.ph';
        profileEmail.textContent = displayEmail;
        console.log('✅ Updated profile email to:', displayEmail);
    } else {
        console.log('❌ Profile email element not found');
    }
    
    // Update profile role
    const profileRole = document.getElementById('profileUserRole');
    if (profileRole) {
        const displayRole = userData.user_type || userData.userType || 'ADMIN';
        profileRole.textContent = displayRole;
        console.log('✅ Updated profile role to:', displayRole);
    } else {
        console.log('❌ Profile role element not found');
    }

    // Update profile access/location
    const profileAccess = document.getElementById('profileUserAccess');
    if (profileAccess) {
        let locationText = 'Location not set';
        
        // Check for address and municipality in various formats
        if (userData.address && userData.municipality) {
            locationText = `${userData.address}, ${userData.municipality}`;
        } else if (userData.municipality) {
            locationText = userData.municipality;
        } else if (userData.address) {
            locationText = userData.address;
        } else if (userData.location) {
            locationText = userData.location;
        }
        
        profileAccess.textContent = locationText;
        console.log('✅ Updated profile location to:', locationText);
    } else {
        console.log('❌ Profile access element not found');
    }
    
    // Update profile title based on role
    const profileTitle = document.getElementById('profileUserTitle');
    if (profileTitle) {
        const userRole = userData.user_type || userData.userType || 'ADMIN';
        if (userRole === 'SUPER ADMIN') {
            profileTitle.textContent = 'System Administrator';
        } else if (userRole === 'SUB ADMIN') {
            profileTitle.textContent = 'Municipal Administrator';
        } else {
            profileTitle.textContent = 'Administrator';
        }
        console.log('Updated profile title for role:', userRole);
    }
    
    // Update profile images
    console.log('=== PROCESSING PROFILE IMAGE ===');
    
    if (userData.image_url && userData.image_url.trim() !== '' && userData.image_url !== 'null') {
        console.log('✅ Profile image found - processing...');
        
        // Clean up the image URL
        let cleanImageUrl = userData.image_url.trim();
        if (cleanImageUrl.startsWith('"') && cleanImageUrl.endsWith('"')) {
            cleanImageUrl = cleanImageUrl.slice(1, -1);
        }
        
        // Get image elements
        const userAvatarImage = document.getElementById('userAvatarImage');
        const userAvatarIcon = document.getElementById('userAvatarIcon');
        const profileAvatarImage = document.getElementById('profileAvatarImage');
        const profileAvatarIcon = document.getElementById('profileAvatarIcon');
        
        // Update main avatar (in navbar)
        if (userAvatarImage && userAvatarIcon) {
            userAvatarImage.src = cleanImageUrl;
            userAvatarImage.style.display = 'block';
            userAvatarIcon.style.display = 'none';
            
            userAvatarImage.onerror = function() {
                console.error('❌ Main avatar image failed to load');
                this.style.display = 'none';
                userAvatarIcon.style.display = 'block';
            };
            
            userAvatarImage.onload = function() {
                console.log('✅ Main avatar image loaded successfully');
            };
        }
        
        // Update dropdown avatar
        if (profileAvatarImage && profileAvatarIcon) {
            profileAvatarImage.src = cleanImageUrl;
            profileAvatarImage.style.display = 'block';
            profileAvatarIcon.style.display = 'none';
            
            profileAvatarImage.onerror = function() {
                console.error('❌ Dropdown avatar image failed to load');
                this.style.display = 'none';
                profileAvatarIcon.style.display = 'block';
            };
            
            profileAvatarImage.onload = function() {
                console.log('✅ Dropdown avatar image loaded successfully');
            };
        }
        
    } else {
        console.log('❌ No profile image found - showing default icons');
        
        // Get image elements
        const userAvatarImage = document.getElementById('userAvatarImage');
        const userAvatarIcon = document.getElementById('userAvatarIcon');
        const profileAvatarImage = document.getElementById('profileAvatarImage');
        const profileAvatarIcon = document.getElementById('profileAvatarIcon');
        
        // Show icons when no image
        if (userAvatarImage && userAvatarIcon) {
            userAvatarImage.style.display = 'none';
            userAvatarIcon.style.display = 'block';
        }
        if (profileAvatarImage && profileAvatarIcon) {
            profileAvatarImage.style.display = 'none';
            profileAvatarIcon.style.display = 'block';
        }
    }
    
    console.log('=== PROFILE DISPLAY UPDATE COMPLETE ===');
}

// Profile dropdown toggle function (if not already defined)
if (typeof toggleProfileDropdown === 'undefined') {
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }
    
    // Make it globally available
    window.toggleProfileDropdown = toggleProfileDropdown;
}

// Sign out function (if not already defined)
if (typeof signOut === 'undefined') {
    function signOut() {
        if (confirm('Are you sure you want to sign out?')) {
            sessionStorage.removeItem('currentUser');
            window.location.href = 'sign_in_form.html';
        }
    }
    
    // Make it globally available
    window.signOut = signOut;
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const userAvatar = document.querySelector('.user-avatar');
    
    if (dropdown && userAvatar && !userAvatar.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Test function for manual testing
function testProfileUpdate() {
    console.log('Testing profile update with sample data...');
    updateProfileDisplay({
        name: 'Test User',
        email: 'test@anihan.gov.ph',
        user_type: 'SUB ADMIN',
        image_url: null,
        address: 'Barangay Poblacion',
        municipality: 'Lipa City'
    });
}

// Function to manually refresh profile from database
async function refreshProfileFromDatabase() {
    try {
        console.log('=== MANUALLY REFRESHING PROFILE FROM DATABASE ===');
        
        // Get current user from session
        const currentUser = JSON.parse(sessionStorage.getItem('currentUser') || '{}');
        console.log('Current user from session:', currentUser);
        
        if (!currentUser.id) {
            console.log('No user ID found, reloading profile system...');
            await loadCurrentUserProfile();
            return;
        }
        
        // Fetch fresh data from admin_accounts table
        const { data: userData, error } = await supabaseClient
            .from('admin_accounts')
            .select('*')
            .eq('id', currentUser.id)
            .single();
        
        if (error) {
            console.error('Error fetching profile data:', error);
            return;
        }
        
        if (userData) {
            console.log('Fresh profile data from database:', userData);
            
            // Update session storage with fresh data
            const userSession = {
                id: userData.id,
                name: userData.name,
                email: userData.email,
                userType: userData.user_type,
                image_url: userData.image_url,
                address: userData.address,
                municipality: userData.municipality
            };
            sessionStorage.setItem('currentUser', JSON.stringify(userSession));
            
            // Update profile display
            updateProfileDisplay(userData);
            
            console.log('✅ Profile refreshed successfully');
            console.log('Address:', userData.address);
            console.log('Municipality:', userData.municipality);
        } else {
            console.log('No profile data found');
        }
        
    } catch (error) {
        console.error('Error refreshing profile:', error);
    }
}

// Make test function available globally
window.testProfileUpdate = testProfileUpdate;
window.refreshProfileFromDatabase = refreshProfileFromDatabase;

console.log('Shared profile system loaded successfully');

console.log(`
=== PROFILE DEBUG FUNCTIONS ===
If the profile location is showing "Loading..." try these functions:

1. refreshProfileFromDatabase() - Reload profile from database
2. testProfileUpdate() - Test with sample address data

Example:
1. Open browser console (F12)
2. Run: refreshProfileFromDatabase()
3. Check if location now shows address + municipality

The location should show in format: "Address, Municipality" or just "Municipality" if no address.
`);
