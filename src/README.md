# Perfume Reseller Management System

## Overview
A B2B wholesale perfume distribution platform connecting the business owner with authorized resellers. The system manages inventory, orders, and payments with a personal, relationship-based approach.

## Business Model

### Core Concept
- **Closed System**: No public access - resellers must be approved by admin
- **Wholesale Distribution**: Owner sells perfumes to resellers at tiered wholesale prices
- **Manual Processes**: Personal touch with admin oversight on approvals and payments
- **Installment Support**: Flexible payment terms for trusted resellers

### User Acquisition Flow
1. Interested party contacts owner via email/Facebook
2. Owner vets the applicant
3. Admin creates user account with credentials
4. Reseller receives login information
5. Reseller can now place orders

## System Architecture

### Tech Stack
- **Backend**: Laravel 12.x (PHP 8.2)
- **Frontend**: Angular
- **Authentication**: Laravel Sanctum (API tokens)
- **Database**: MySQL
- **API Type**: RESTful API
- **Architecture**: Separated frontend/backend (SPA)

### Database Schema

#### Core Tables (15 total)

**User Management**
- `roles` - User role definitions (Admin, Reseller, etc.)
- `users` - User accounts with authentication

**Product Catalog**
- `categories` - Perfume categories
- `perfumes` - Perfume base information
- `perfume_sizes` - Available sizes (50ml, 100ml, etc.)
- `perfume_tiers` - Pricing tiers (Tier A, Tier B, etc.)
- `perfume_variants` - Combination of perfume + size + tier with specific price and stock

**Order Management**
- `orders` - Order headers
- `order_items` - Individual items in orders
- `order_status` - Order status lookup (Pending, Approved, Shipped, etc.)
- `payment_status` - Payment status lookup (Unpaid, Partial, Paid)

**Payment Tracking**
- `payments` - Payment records (supports multiple payments per order for installments)
- `payment_modes` - Payment methods (Cash, GCash, Bank Transfer, etc.)

**Discount System**
- `discount_types` - Discount type categories (Percentage, Fixed Amount, etc.)
- `discounts` - Discount definitions with codes and validity periods

### Key Relationships

```
roles (1) ----< (N) users
users (1) ----< (N) orders
categories (1) ----< (N) perfumes
perfumes (1) ----< (N) perfume_variants
perfume_sizes (1) ----< (N) perfume_variants
perfume_tiers (1) ----< (N) perfume_variants
orders (1) ----< (N) order_items
orders (1) ----< (N) payments
perfume_variants (1) ----< (N) order_items
discounts (1) ----< (N) order_items (nullable)
order_status (1) ----< (N) orders
payment_status (1) ----< (N) orders
payment_modes (1) ----< (N) payments
discount_types (1) ----< (N) discounts
```

## Business Rules & Logic

### Pricing System
- **Variant-Based Pricing**: Each combination of perfume + size + tier has unique price
- **Price Preservation**: `order_items.price_at_reservation` locks price at order time
- **Tiered Discounts**: Resellers see different prices based on their tier assignment

### Order Workflow
1. **Creation**: Reseller browses catalog and creates order
2. **Editing**: Reseller can modify order before submission/approval
3. **Submission**: Reseller submits order for review
4. **Approval**: Admin reviews and approves order
5. **Payment**: Admin records payments as received (manual process)
6. **Fulfillment**: Order processed and shipped
7. **Completion**: Order marked complete

### Payment Management
- **No Integrated Gateway**: All payments recorded manually by admin
- **Multiple Payment Methods**: Cash, GCash, Bank Transfer, etc.
- **Installment Support**: Single order can have multiple payment records
- **Manual Status Updates**: Admin updates payment status after verification

### Inventory Management
- **Stock Tracking**: Per variant (perfume + size + tier)
- **Manual Updates**: Admin adjusts stock quantities
- **Reservation**: Stock reserved when order is created (optional implementation)

### User Permissions

#### Admin Role
- ✅ Create/manage user accounts
- ✅ Full CRUD on all entities
- ✅ Approve/reject orders
- ✅ Record and manage payments
- ✅ Update order statuses
- ✅ Manage inventory (stock levels, prices)
- ✅ Create/modify discounts
- ✅ Add new lookup values (sizes, tiers, categories, etc.)

#### Reseller Role
- ✅ View product catalog
- ✅ Create orders
- ✅ Edit own orders (before approval)
- ✅ View own order history
- ✅ View own payment records
- ❌ Cannot create other users
- ❌ Cannot approve orders
- ❌ Cannot access other resellers' data
- ❌ Cannot modify inventory or prices

## API Endpoints Structure

### Authentication
```
POST   /api/login          - User login
POST   /api/logout         - User logout
GET    /api/user           - Get authenticated user
```

### Users (Admin only for CRUD)
```
GET    /api/users          - List all users
POST   /api/users          - Create new user
GET    /api/users/{id}     - Get user details
PUT    /api/users/{id}     - Update user
DELETE /api/users/{id}     - Delete user
```

### Perfumes (Admin CRUD, All view)
```
GET    /api/perfumes       - List all perfumes
POST   /api/perfumes       - Create perfume (admin)
GET    /api/perfumes/{id}  - Get perfume details
PUT    /api/perfumes/{id}  - Update perfume (admin)
DELETE /api/perfumes/{id}  - Delete perfume (admin)
```

### Perfume Variants (Admin CRUD, All view)
```
GET    /api/variants       - List all variants
POST   /api/variants       - Create variant (admin)
GET    /api/variants/{id}  - Get variant details
PUT    /api/variants/{id}  - Update variant (admin)
DELETE /api/variants/{id}  - Delete variant (admin)
```

### Orders (Reseller own, Admin all)
```
GET    /api/orders         - List orders (filtered by role)
POST   /api/orders         - Create order
GET    /api/orders/{id}    - Get order details
PUT    /api/orders/{id}    - Update order
DELETE /api/orders/{id}    - Delete order
```

### Order Items (Managed through orders)
```
GET    /api/order-items    - List order items
POST   /api/order-items    - Add item to order
PUT    /api/order-items/{id} - Update order item
DELETE /api/order-items/{id} - Remove order item
```

### Payments (Admin records, All view own)
```
GET    /api/payments       - List payments
POST   /api/payments       - Record payment (admin)
GET    /api/payments/{id}  - Get payment details
PUT    /api/payments/{id}  - Update payment (admin)
DELETE /api/payments/{id}  - Delete payment (admin)
```

### Discounts (Admin CRUD, All view)
```
GET    /api/discounts      - List discounts
POST   /api/discounts      - Create discount (admin)
GET    /api/discounts/{id} - Get discount details
PUT    /api/discounts/{id} - Update discount (admin)
DELETE /api/discounts/{id} - Delete discount (admin)
```

### Lookup Tables (All list, Admin CRUD)
```
GET    /api/categories
GET    /api/sizes
GET    /api/tiers
GET    /api/roles
GET    /api/order-statuses
GET    /api/payment-statuses
GET    /api/payment-modes
GET    /api/discount-types

POST/PUT/DELETE (admin only for all above)
```

## Key Workflows

### 1. Order Creation Workflow
```
1. Reseller logs in
2. Browses perfume catalog
3. Views variants with prices (based on tier)
4. Adds items to cart/order
5. Applies discount code (optional)
6. Reviews order total
7. Submits order
8. Order status: "Pending Approval"
```

### 2. Order Approval Workflow
```
1. Admin views pending orders
2. Reviews order details
3. Checks inventory availability
4. Approves or rejects order
5. If approved: Order status → "Approved"
6. If rejected: Order status → "Rejected" with reason
7. Notification sent to reseller (optional)
```

### 3. Payment Recording Workflow
```
1. Reseller makes payment (offline - cash/GCash/etc.)
2. Reseller notifies admin (via phone/message)
3. Admin verifies payment received
4. Admin creates payment record:
   - Links to order
   - Records amount
   - Records payment mode
   - Records reference number
5. If full amount paid:
   - Payment status → "Paid"
6. If partial payment (installment):
   - Payment status → "Partial"
   - Can add more payments later
7. Order can have multiple payment records
```

### 4. Inventory Management Workflow
```
1. Admin receives new stock
2. Admin creates perfume (if new)
3. Admin creates variants:
   - Selects perfume
   - Selects size
   - Selects tier
   - Sets price
   - Sets stock quantity
4. Updates existing variants as needed
5. Stock decrements when orders approved (optional)
```

## Implementation Notes

### Current Phase
- Manual payment processing (no payment gateway integration)
- Basic order management
- Admin-driven user creation
- Simple inventory tracking

### Future Enhancements (Potential)
- Payment gateway integration (PayMongo, PayPal, etc.)
- Automated stock reservation on order creation
- Email/SMS notifications for order updates
- Advanced reporting and analytics
- Reseller tier auto-upgrade based on volume
- Mobile app for resellers
- Order tracking system
- Automated invoice generation
- Integration with shipping providers

## Data Constraints

### Important Validations
- `quantity` > 0
- `price`, `amount`, `value` ≥ 0
- `stock_quantity` ≥ 0
- `end_date` > `start_date` (discounts)
- Unique constraints on codes, emails, etc.
- Foreign key integrity enforced with cascades

### Cascade Rules
**ON DELETE CASCADE:**
- Delete perfume → deletes all its variants
- Delete order → deletes all order items and payments

**ON DELETE RESTRICT:**
- Cannot delete role if users exist
- Cannot delete category if perfumes exist
- Cannot delete size/tier if variants exist
- Cannot delete variant if in existing orders

**ON DELETE SET NULL:**
- Delete discount → order items keep prices but lose discount reference

## Getting Started

### Prerequisites
- **Backend:**
  - PHP 8.2+
  - Composer
  - Laravel 12.x
  - MySQL 8.0+
  
- **Frontend:**
  - Node.js 18+
  - Angular CLI
  - Angular 17+ (or your version)

### Installation

**Backend Setup:**
```bash
# Clone repository
git clone [repository-url]
cd [project-backend]

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Install Sanctum
php artisan install:api

# Configure CORS for Angular
# Update config/cors.php to allow your Angular app origin

# Start server
php artisan serve
```

**Frontend Setup:**
```bash
# Navigate to Angular project
cd [project-frontend]

# Install dependencies
npm install

# Update environment files
# Set API_URL to your Laravel backend (e.g., http://localhost:8000/api)

# Start development server
ng serve
```

### Initial Seeders Needed
- Roles (Admin, Reseller)
- Order Statuses (Pending, Approved, Processing, Shipped, Completed, Cancelled)
- Payment Statuses (Unpaid, Partial, Paid)
- Payment Modes (Cash, GCash, Bank Transfer)
- Discount Types (Percentage, Fixed Amount)
- Categories (sample)
- Sizes (50ml, 100ml, etc.)
- Tiers (Tier A, Tier B, Tier C)

## Security Considerations

- All API routes protected with Sanctum authentication
- Role-based access control (RBAC)
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- Input validation on all endpoints
- Rate limiting on authentication endpoints

## Contact & Support

For questions about becoming a reseller or system access:
- Email: [business-email]
- Facebook: [fb-page-url]

---

**System Version:** 1.0  
**Last Updated:** 2024  
**Documentation Status:** Complete