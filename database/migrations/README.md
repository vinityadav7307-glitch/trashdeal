# TrashDeal — Laravel Migration Files

## How to use these files

1. Copy all `.php` files into your Laravel project:
   ```
   database/migrations/
   ```

2. Run all migrations:
   ```bash
   php artisan migrate
   ```

3. To rollback all:
   ```bash
   php artisan migrate:rollback
   ```

4. To reset and re-run fresh:
   ```bash
   php artisan migrate:fresh
   ```

## Migration order (important — do NOT change filenames)

| File | Table | Depends on |
|------|-------|-----------|
| 000001 | users | — |
| 000002 | waste_collectors | users |
| 000003 | pickups | users, waste_collectors |
| 000004 | waste_scans | users, pickups |
| 000005 | rewards | — |
| 000006 | point_transactions | users, pickups, rewards |
| 000007 | otp_verifications | — |
| 000008 | personal_access_tokens | — (Sanctum) |
| 000009 | notifications | users |

## Status flows

### Pickup status
pending → assigned → picked_up → weighed → verified → completed
                                                      → cancelled (any stage)

### Point transaction types
- earned_pickup    — points from waste pickup
- earned_scan      — points from camera scan
- earned_referral  — bonus from referring a friend
- redeemed         — points spent on a reward
- bonus            — admin manual bonus
- deducted         — penalty or reversal

### Scan types
- waste_identification — camera scans waste to detect type
- collector_qr         — user scans collector QR to verify pickup
- weight_verification  — DDC staff records final weight
