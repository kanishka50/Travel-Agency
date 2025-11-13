# Stripe Payment Integration Setup Guide

## Overview
This guide will help you set up Stripe payment integration with local webhook testing using the Stripe CLI.

## Prerequisites
- Stripe CLI installed at `C:\stripe\`
- Stripe account with test mode enabled
- Laravel application running on `http://localhost:8000`

## Step 1: Get Your Stripe API Keys

1. Log in to your Stripe Dashboard: https://dashboard.stripe.com/test/dashboard
2. Click on "Developers" in the left sidebar
3. Click on "API keys"
4. Copy the following keys:
   - **Publishable key** (starts with `pk_test_`)
   - **Secret key** (starts with `sk_test_`)

## Step 2: Update .env File

Update your `.env` file with your actual Stripe test keys:

```env
# Stripe API Keys
STRIPE_KEY=pk_test_YOUR_ACTUAL_PUBLISHABLE_KEY_HERE
STRIPE_SECRET=sk_test_YOUR_ACTUAL_SECRET_KEY_HERE
STRIPE_WEBHOOK_SECRET=whsec_YOUR_WEBHOOK_SECRET_HERE
```

Note: We'll get the `STRIPE_WEBHOOK_SECRET` in Step 4.

## Step 3: Login to Stripe CLI

Open a new Command Prompt or PowerShell window and navigate to your Stripe CLI directory:

```bash
cd C:\stripe
```

Login to Stripe CLI:

```bash
stripe login
```

This will open your browser to authenticate. Follow the prompts and grant access.

## Step 4: Forward Webhooks to Your Local Application

Start the Stripe CLI webhook forwarding with the following command:

```bash
stripe listen --forward-to http://localhost:8000/webhook/stripe
```

**Important:** After running this command, the Stripe CLI will display a webhook signing secret that looks like:

```
> Ready! Your webhook signing secret is whsec_xxxxxxxxxxxxxxxxxxxxxx
```

**Copy this webhook secret** and update your `.env` file:

```env
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxx
```

**Keep this terminal window open** while testing payments. The webhooks will only work while the `stripe listen` command is running.

## Step 5: Trigger Test Payments

You can now test the payment flow:

###  Test Card Numbers

Use these test cards in Stripe Checkout:

**Successful Payment:**
- Card Number: `4242 4242 4242 4242`
- Expiry: Any future date (e.g., `12/34`)
- CVC: Any 3 digits (e.g., `123`)
- ZIP: Any 5 digits (e.g., `12345`)

**Payment Declined:**
- Card Number: `4000 0000 0000 0002`
- Use same expiry, CVC, and ZIP as above

**Requires Authentication (3D Secure):**
- Card Number: `4000 0025 0000 3155`
- Complete the authentication in the test popup

More test cards: https://stripe.com/docs/testing

## Step 6: Test the Complete Flow

1. **Create a booking** as a tourist
2. **Click "Proceed to Payment"** on the booking details page
3. **Complete payment** using a test card
4. **Check the Stripe CLI terminal** - you should see webhook events being received
5. **Verify booking status** changes to "confirmed" in your application

## Webhook Events We Handle

Our application listens for these Stripe webhook events:

- `checkout.session.completed` - When payment is successful
- `payment_intent.succeeded` - When payment intent succeeds
- `payment_intent.payment_failed` - When payment fails

## Troubleshooting

### Webhooks Not Working

1. **Check if Stripe CLI is running:**
   - Make sure the `stripe listen` command is still running
   - You should see "Ready! Listening for events..." in the terminal

2. **Verify webhook URL:**
   - The webhook URL should be: `http://localhost:8000/webhook/stripe`
   - Check that your Laravel app is running on port 8000

3. **Check webhook secret:**
   - Make sure the `STRIPE_WEBHOOK_SECRET` in `.env` matches the one from `stripe listen`
   - Restart your Laravel server after updating `.env`

### Payment Not Completing

1. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Stripe Dashboard:**
   - Go to https://dashboard.stripe.com/test/payments
   - Look for recent payment attempts

3. **Check database:**
   - Verify the booking status in the `bookings` table
   - Check if `stripe_session_id` and `stripe_payment_id` are populated

### Clear Cache After .env Changes

After updating the `.env` file, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Using Stripe CLI for Testing Webhooks Manually

You can trigger specific webhook events manually for testing:

```bash
# Trigger a successful payment event
stripe trigger checkout.session.completed

# Trigger a failed payment event
stripe trigger payment_intent.payment_failed
```

## Production Setup

When moving to production:

1. Get your **live** API keys from Stripe Dashboard (Production mode)
2. Update `.env` with live keys (keys start with `pk_live_` and `sk_live_`)
3. Set up a **real webhook endpoint** in Stripe Dashboard:
   - Go to Developers > Webhooks
   - Add endpoint: `https://yourdomain.com/webhook/stripe`
   - Select events: `checkout.session.completed`, `payment_intent.succeeded`, `payment_intent.payment_failed`
   - Copy the signing secret and update `STRIPE_WEBHOOK_SECRET` in production `.env`

## Additional Resources

- Stripe CLI Documentation: https://stripe.com/docs/stripe-cli
- Stripe Testing Guide: https://stripe.com/docs/testing
- Stripe Webhook Guide: https://stripe.com/docs/webhooks
- Laravel Cashier Documentation: https://laravel.com/docs/billing

## Quick Reference Commands

```bash
# Login to Stripe CLI
cd C:\stripe
stripe login

# Start webhook forwarding
stripe listen --forward-to http://localhost:8000/webhook/stripe

# Trigger test events
stripe trigger checkout.session.completed
stripe trigger payment_intent.payment_failed

# View Stripe logs
stripe logs tail
```

---

**Happy Testing!** 🎉
