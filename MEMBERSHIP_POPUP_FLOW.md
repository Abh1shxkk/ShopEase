# Membership Popup Flow Documentation

## Overview
The membership popup displays after user login to encourage membership subscriptions. It shows 3 membership plans with a clean, elegant design matching the website aesthetic.

## Flow Diagram

```
User Login → Email + Password → OTP Sent → OTP Verification → Login Success
                                                                      ↓
                                                            Check Conditions:
                                                            1. User is authenticated
                                                            2. User is not a member
                                                            3. User hasn't hidden popup
                                                                      ↓
                                                            Set session flag:
                                                            'show_membership_popup' = true
                                                                      ↓
                                                            Redirect to /shop
                                                                      ↓
                                                            Shop page loads
                                                                      ↓
                                                            Popup component checks session
                                                                      ↓
                                                            Wait 4.5 seconds
                                                                      ↓
                                                            Show popup with fade-in animation
```

## Technical Implementation

### 1. Login Flow (AuthController.php)

**Location**: `app/Http/Controllers/AuthController.php`

**Key Method**: `verifyOtp()`

```php
// After successful OTP verification
if (!$user->hide_membership_popup) {
    session(['show_membership_popup' => true]);
}

// Redirect to shop
return redirect()->intended('/shop');
```

### 2. Popup Component (membership-popup.blade.php)

**Location**: `resources/views/components/membership-popup.blade.php`

**Condition Check**:
```php
@if(session('show_membership_popup') && auth()->check() && !auth()->user()->isMember())
```

**Structure**:
- Overlay with film grain background (no blur)
- Centered popup container
- Scrollable content area (max-height: 85vh)
- 3 membership plans in grid layout
- Footer with checkbox and button

**Timing**:
```javascript
setTimeout(() => {
    // Show popup after 4.5 seconds
}, 4500);
```

### 3. User Interactions

#### A. Select Plan
- Clicks "Select Plan" button
- Dismisses popup
- Redirects to membership checkout

#### B. Don't Show Again
- Checks "Don't show this again" checkbox
- Clicks "Maybe Later" or close button
- Sends POST to `/membership/popup/hide`
- Updates `users.hide_membership_popup = true`
- Popup won't show on future logins

#### C. Maybe Later
- Clicks "Maybe Later" without checkbox
- Sends POST to `/membership/popup/dismiss`
- Clears session flag only
- Popup will show on next login

#### D. Click Outside / Escape Key
- Same as "Maybe Later"
- Dismisses for current session only

### 4. Routes

**Location**: `routes/web.php`

```php
Route::post('/membership/popup/hide', [AuthController::class, 'hideMembershipPopup'])
    ->name('membership.popup.hide');

Route::post('/membership/popup/dismiss', [AuthController::class, 'dismissMembershipPopup'])
    ->name('membership.popup.dismiss');
```

### 5. Database Fields

**Table**: `users`

**Fields**:
- `hide_membership_popup` (boolean, default: false)
- `is_member` (boolean, default: false)
- `membership_expires_at` (timestamp, nullable)

## Design Features

### Visual Design
- **Background**: Film grain overlay with 85% opacity slate background
- **Popup**: White background, clean slate colors
- **Typography**: Serif fonts for headings, sans-serif for body
- **Animations**: Smooth fade-in and slide-up effects
- **Shadow**: Deep shadow for depth (0 25px 50px -12px rgba(0, 0, 0, 0.4))

### User Experience
- **Delay**: 4.5 seconds after page load (not immediate)
- **No Scroll**: Body scroll prevented when popup is open
- **Responsive**: Adapts to screen size
- **Accessible**: Keyboard support (Escape to close)
- **Click Outside**: Closes popup
- **No Blur**: Background uses film grain only (matches website)

### Layout
- **Width**: Max 4xl (56rem / 896px)
- **Height**: Max 85vh (85% of viewport height)
- **Padding**: 12 (3rem / 48px)
- **Plans**: 3 columns, equal width
- **Spacing**: Consistent 6 (1.5rem / 24px) gap

## Testing Checklist

- [ ] Login with email + password
- [ ] Verify OTP
- [ ] Redirected to /shop
- [ ] Popup appears after 4.5 seconds
- [ ] 3 plans displayed correctly
- [ ] "Don't show again" checkbox works
- [ ] "Maybe Later" button works
- [ ] Click outside closes popup
- [ ] Escape key closes popup
- [ ] Select plan redirects correctly
- [ ] Popup doesn't show for members
- [ ] Popup doesn't show if hidden permanently
- [ ] Body scroll prevented when popup open
- [ ] Animations smooth and elegant

## Troubleshooting

### Popup Not Showing

1. **Check session**: Verify `show_membership_popup` is set
   ```php
   dd(session('show_membership_popup'));
   ```

2. **Check user status**: Verify user is not a member
   ```php
   dd(auth()->user()->isMember());
   ```

3. **Check hide flag**: Verify user hasn't hidden popup
   ```php
   dd(auth()->user()->hide_membership_popup);
   ```

4. **Check browser console**: Look for JavaScript errors

5. **Clear cache**:
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

### Buttons Outside Popup

**Fixed**: Wrapped content in scrollable div with `overflow-y-auto`

**Before**:
```html
<div id="membership-popup">
    <div class="p-12">
        <!-- Content -->
    </div>
    <!-- Buttons were here, outside scrollable area -->
</div>
```

**After**:
```html
<div id="membership-popup">
    <div class="overflow-y-auto" style="max-height: 85vh;">
        <div class="p-12">
            <!-- Content -->
            <!-- Buttons now inside scrollable area -->
        </div>
    </div>
</div>
```

## Future Enhancements

1. **A/B Testing**: Test different delays (3s, 5s, 7s)
2. **Analytics**: Track popup conversion rates
3. **Personalization**: Show different plans based on user behavior
4. **Animation Variants**: Test different entrance animations
5. **Mobile Optimization**: Adjust layout for smaller screens
6. **Exit Intent**: Show popup when user tries to leave
7. **Frequency Cap**: Limit popup to once per week if dismissed

## Related Files

- `app/Http/Controllers/AuthController.php` - Login and popup logic
- `app/Models/User.php` - User model with isMember() method
- `resources/views/components/membership-popup.blade.php` - Popup component
- `resources/views/layouts/shop.blade.php` - Layout including popup
- `routes/web.php` - Popup routes
- `database/migrations/2025_01_06_300000_add_login_otp_fields_to_users_table.php` - Database fields
