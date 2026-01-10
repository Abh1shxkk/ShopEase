# ShopEase Multi-Vendor Seller Flow

## 🎯 Simple Overview

Think of it like this: **ShopEase is a marketplace** (like Amazon/Flipkart) where multiple sellers can list and sell their products.

---

## 📋 Complete Seller Journey

### Step 1: User Becomes a Seller
```
Regular User → Clicks "Become a Seller" → Fills Application Form → Submits
```
- **URL:** `/become-seller`
- **Access:** Any logged-in user
- **What they fill:** Store name, business details, contact info, bank details (optional)

### Step 2: Admin Reviews Application
```
Admin Dashboard → Sellers → View Application → Approve/Reject
```
- **URL:** `/admin/sellers`
- **Admin can:** Approve, Reject (with reason), or set auto-approve in settings

### Step 3: Seller Gets Approved
```
Seller receives approval → Can now access Seller Dashboard
```
- **URL:** `/seller`
- **Status changes:** `pending` → `approved`

### Step 4: Seller Adds Products
```
Seller Dashboard → Products → Add Product → Submit for Approval
```
- **URL:** `/seller/products/create`
- Products may need admin approval (configurable)

### Step 5: Customer Buys Product
```
Customer browses shop → Adds seller's product to cart → Completes purchase
```
- Order is created with seller's product
- System calculates seller's earnings (minus commission)

### Step 6: Seller Earns Money
```
Order Delivered → Earnings credited to Seller Wallet → Seller requests Payout
```
- **Commission:** Platform takes X% (default 10%)
- **Example:** ₹1000 sale → Platform gets ₹100, Seller gets ₹900

### Step 7: Seller Gets Paid
```
Seller requests payout → Admin processes → Money transferred to bank
```
- **URL:** `/seller/payouts`
- Minimum payout amount applies (default ₹500)

---

## 🔗 Key URLs

### For Sellers:
| Page | URL | Description |
|------|-----|-------------|
| Register | `/become-seller` | Apply to become a seller |
| Dashboard | `/seller` | Main seller dashboard |
| Products | `/seller/products` | Manage product listings |
| Add Product | `/seller/products/create` | Add new product |
| Orders | `/seller/orders` | View orders with your products |
| Payouts | `/seller/payouts` | Request money withdrawal |
| Settings | `/seller/profile` | Store settings & bank details |

### For Admin:
| Page | URL | Description |
|------|-----|-------------|
| Sellers List | `/admin/sellers` | View/approve sellers |
| Seller Products | `/admin/seller-products` | Approve seller products |
| Payouts | `/admin/seller-payouts` | Process payout requests |
| Settings | `/admin/seller-settings` | Configure commission, auto-approve |

---

## 💰 How Money Flows

```
Customer pays ₹1000
        ↓
    Order Created
        ↓
Platform Commission (10%) = ₹100
Seller Earnings = ₹900
        ↓
    [Order Delivered]
        ↓
₹900 added to Seller Wallet
        ↓
Seller requests payout
        ↓
Admin processes payout
        ↓
Money sent to Seller's bank
```

---

## ⚙️ Admin Settings

Located at `/admin/seller-settings`:

| Setting | Default | Description |
|---------|---------|-------------|
| Commission Rate | 10% | Platform fee on each sale |
| Minimum Payout | ₹500 | Minimum amount for withdrawal |
| Auto-approve Sellers | No | Skip manual approval |
| Auto-approve Products | No | Skip product review |

---

## 🔄 Status Flow

### Seller Status:
```
pending → approved → (can be suspended)
        ↘ rejected
```

### Product Approval:
```
pending → approved → (visible in shop)
        ↘ rejected
```

### Earnings Status:
```
pending (order not delivered) → processed (order delivered) → paid (payout completed)
```

---

## 🚀 Quick Test

1. **Login as regular user**
2. **Go to:** `/become-seller`
3. **Fill form** and submit
4. **Login as admin**
5. **Go to:** `/admin/sellers`
6. **Approve** the seller
7. **Login as seller**
8. **Go to:** `/seller`
9. **Add products** and start selling!
