# Membership Popup Testing Guide

## Quick Test Steps

### 1. Test with Existing User

1. **Login to the application**
   - Go to: `http://127.0.0.1:8000/login`
   - Enter email and password
   - Verify OTP from email
   - Should redirect to `/shop`

2. **Verify popup appears**
   - Wait 4.5 seconds after page load
   - Popup should fade in smoothly
   - Should show 3 membership plans
   - Background should have film grain (no blur)

3. **Test interactions**
   - Click outside popup → Should close
   - Press Escape key → Should close
   - Check "Don't show again" → Click "Maybe Later" → Should not show on next login
   - Click "Select Plan" → Should redirect to membership checkout

### 2. Test with Debug Route

1. **Login first** (if not already logged in)

2. **Visit debug route**
   ```
   http://127.0.0.1:8000/test-popup
   ```
   - This sets the session flag manually
   - Redirects to `/shop`
   - Popup should appear after 4.5 seconds

### 3. Test Different User States

#### A. Non-Member User (Should Show Popup)
```sql
-- Check user status
SELECT id, name, email, is_member, hide_membership_popup 
FROM users 
WHERE email = 'your@email.com';

-- Reset popup preference
UPDATE users 
SET hide_membership_popup = 0 
WHERE email = 'your@email.com';
```

#### B. Member User (Should NOT Show Popup)
```sql
-- Make user a member
UPDATE users 
SET is_member = 1, 
    membership_expires_at = DATE_ADD(NOW(), INTERVAL 1 MONTH)
WHERE email = 'your@email.com';
```

#### C. User Who Hidden Popup (Should NOT Show Popup)
```sql
-- User clicked "Don't show again"
UPDATE users 
SET hide_membership_popup = 1 
WHERE email = 'your@email.com';
```

### 4. Browser Console Checks

Open browser console (F12) and check for:

1. **Session flag**
   ```javascript
   // Should see this in Network tab after login
   // Response should include session data
   ```

2. **JavaScript errors**
   - No errors should appear
   - Popup should log: "Membership popup session set for user: X"

3. **Element visibility**
   ```javascript
   // Check if popup exists
   document.getElementById('membership-popup-overlay')
   
   // Check if popup is visible
   window.getComputedStyle(document.getElementById('membership-popup-overlay')).display
   ```

### 5. Visual Checks

- [ ] Popup is centered on screen
- [ ] Background has film grain texture
- [ ] Background is NOT blurred
- [ ] Popup has white background
- [ ] 3 plans are displayed side by side
- [ ] Plans have proper spacing
- [ ] "Don't show again" checkbox is visible
- [ ] "Maybe Later" button is visible
- [ ] Close button (X) is visible in top-right
- [ ] All buttons are INSIDE the popup (not outside)
- [ ] Popup has shadow effect
- [ ] Text is readable and properly styled

### 6. Animation Checks

- [ ] Popup appears after 4.5 seconds (not immediately)
- [ ] Fade-in animation is smooth
- [ ] Slide-up animation is smooth
- [ ] Close animation is smooth (fade-out)
- [ ] No jarring or jumpy movements

### 7. Functionality Checks

#### A. "Don't Show Again" Flow
1. Check the checkbox
2. Click "Maybe Later"
3. Verify POST request to `/membership/popup/hide`
4. Logout and login again
5. Popup should NOT appear

#### B. "Maybe Later" Flow
1. Don't check the checkbox
2. Click "Maybe Later"
3. Verify POST request to `/membership/popup/dismiss`
4. Logout and login again
5. Popup SHOULD appear again

#### C. "Select Plan" Flow
1. Click "Select Plan" on any plan
2. Should redirect to `/membership/subscribe/{plan}`
3. Popup should close
4. Checkout page should load

#### D. Click Outside Flow
1. Click anywhere outside the popup
2. Popup should close
3. Same as "Maybe Later" (shows again on next login)

#### E. Escape Key Flow
1. Press Escape key
2. Popup should close
3. Same as "Maybe Later" (shows again on next login)

### 8. Mobile Responsive Checks

1. **Resize browser to mobile width** (< 768px)
   - [ ] Popup adjusts to screen size
   - [ ] Plans stack vertically or adjust layout
   - [ ] Text remains readable
   - [ ] Buttons remain accessible
   - [ ] Popup doesn't overflow screen

2. **Test on actual mobile device**
   - [ ] Touch interactions work
   - [ ] Popup is properly sized
   - [ ] No horizontal scroll
   - [ ] Close button is easily tappable

### 9. Performance Checks

- [ ] Popup loads quickly (no lag)
- [ ] Animations are smooth (60fps)
- [ ] No memory leaks (check DevTools)
- [ ] Page remains responsive while popup is open
- [ ] Body scroll is prevented when popup is open
- [ ] Body scroll is restored when popup closes

### 10. Edge Cases

#### A. Multiple Tabs
1. Open two tabs
2. Login in first tab
3. Popup should show in first tab only
4. Switch to second tab
5. Popup should not show (session is shared)

#### B. Back Button
1. Login and see popup
2. Navigate to another page
3. Click back button
4. Popup should not show again (session cleared)

#### C. Refresh Page
1. Login and see popup
2. Refresh page before popup appears
3. Popup should still appear after 4.5 seconds

#### D. Slow Network
1. Throttle network to "Slow 3G"
2. Login and verify OTP
3. Popup should still appear (no network dependency)

## Common Issues and Solutions

### Issue 1: Popup Not Appearing

**Symptoms**: No popup after login

**Checks**:
1. Check browser console for errors
2. Verify session flag is set: `dd(session('show_membership_popup'))`
3. Check user is not a member: `dd(auth()->user()->isMember())`
4. Check hide flag: `dd(auth()->user()->hide_membership_popup)`
5. Clear cache: `php artisan view:clear`

**Solution**:
```php
// In AuthController.php, temporarily force popup
session(['show_membership_popup' => true]);
\Log::info('Popup session set');
```

### Issue 2: Buttons Outside Popup

**Symptoms**: Buttons appear below or outside popup container

**Solution**: Already fixed! Content is wrapped in scrollable div.

**Verify**:
```html
<!-- Should have this structure -->
<div id="membership-popup">
    <div class="overflow-y-auto" style="max-height: 85vh;">
        <div class="p-12">
            <!-- All content including buttons -->
        </div>
    </div>
</div>
```

### Issue 3: Popup Shows Immediately

**Symptoms**: Popup appears right away, not after 4.5 seconds

**Check**:
```javascript
// Should have this delay
setTimeout(() => {
    // Show popup
}, 4500); // 4.5 seconds
```

### Issue 4: Background Scroll Not Prevented

**Symptoms**: Can scroll page while popup is open

**Check**:
```javascript
// Should add class to body
document.body.classList.add('popup-open');
```

```css
/* Should have this CSS */
body.popup-open {
    overflow: hidden !important;
}
```

### Issue 5: Popup Shows for Members

**Symptoms**: Members see popup (shouldn't happen)

**Check**:
```php
// Condition should be
@if(session('show_membership_popup') && auth()->check() && !auth()->user()->isMember())
```

## Success Criteria

✅ All checks pass
✅ No console errors
✅ Smooth animations
✅ Proper timing (4.5 seconds)
✅ All interactions work
✅ Responsive on mobile
✅ Accessible (keyboard support)
✅ Matches website design
✅ No performance issues

## Next Steps After Testing

1. **Monitor Analytics**
   - Track popup views
   - Track conversion rate
   - Track dismiss rate
   - Track "don't show again" rate

2. **Gather Feedback**
   - User surveys
   - A/B testing
   - Heatmaps
   - Session recordings

3. **Optimize**
   - Adjust timing if needed
   - Test different copy
   - Test different layouts
   - Test different plans shown

4. **Scale**
   - Add more plans
   - Add personalization
   - Add exit intent
   - Add frequency capping
