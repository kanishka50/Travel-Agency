# Laravel Task Scheduler Setup

This document explains how to setup the Laravel Task Scheduler to run automated tasks on your Tourism Platform.

---

## 📋 What Gets Automated

The following tasks run automatically:

1. **Booking Status Updates** (Daily at 00:00 / Midnight)
   - `confirmed` → `upcoming` (7 days before tour)
   - `confirmed/upcoming` → `ongoing` (on tour start date)
   - `ongoing` → `completed` (day after tour ends)

2. **Expired Plans Deactivation** (Daily at 01:00 AM)
   - Deactivates seasonal guide plans past their end date
   - Only affects plans with `availability_type = 'date_range'`

---

## ⚙️ Setup Instructions

### **For Development (Windows)**

#### Option 1: Manual Testing
Run the scheduler manually whenever you want to test:

```bash
cd "H:\Travel Agency\tourism-platform"
php artisan schedule:run
```

This checks if any scheduled tasks should run at the current time.

#### Option 2: Windows Task Scheduler (Automated)

**Step 1:** Open Task Scheduler
- Press `Win + R`
- Type `taskschd.msc`
- Press Enter

**Step 2:** Create New Task
- Click "Create Basic Task" in the right panel
- Name: `Laravel Scheduler - Tourism Platform`
- Description: `Runs Laravel task scheduler every minute`
- Click "Next"

**Step 3:** Set Trigger
- Select "Daily"
- Start date: Today
- Start time: 00:00:00 (midnight)
- Recur every: 1 days
- Click "Next"

**Step 4:** Set Action
- Select "Start a program"
- Program/script: `C:\path\to\php.exe` (find your PHP path)
- Add arguments: `artisan schedule:run`
- Start in: `H:\Travel Agency\tourism-platform`
- Click "Next"

**Step 5:** Advanced Settings (Important!)
- After creating, right-click the task → "Properties"
- Go to "Triggers" tab
- Edit the trigger
- Check "Repeat task every: 1 minute"
- For duration of: Indefinitely
- Click "OK"

**Step 6:** Test It
- Right-click the task
- Click "Run"
- Check logs: `storage/logs/laravel.log`

---

### **For Production (Linux/Ubuntu Server)**

#### Setup Cron Job

**Step 1:** Edit crontab
```bash
crontab -e
```

**Step 2:** Add this line (replace path with your actual path)
```bash
* * * * * cd /var/www/tourism-platform && php artisan schedule:run >> /dev/null 2>&1
```

This runs every minute and checks if any scheduled tasks should execute.

**Step 3:** Save and exit
- Press `Ctrl + X`
- Press `Y`
- Press `Enter`

**Step 4:** Verify cron is running
```bash
crontab -l  # List all cron jobs
service cron status  # Check cron service is running
```

---

## 🧪 Testing the Scheduler

### List Scheduled Tasks
```bash
php artisan schedule:list
```

Output:
```
0 0 * * *  php artisan bookings:update-status ........... Next Due: 20 hours from now
0 1 * * *  php artisan plans:deactivate-expired ......... Next Due: 21 hours from now
```

### Run Scheduler Manually
```bash
php artisan schedule:run
```

### Test Individual Commands
```bash
# Test booking status updates
php artisan bookings:update-status

# Test expired plans deactivation
php artisan plans:deactivate-expired
```

---

## 📊 Monitoring

### Check Logs

**View recent logs:**
```bash
cd "H:\Travel Agency\tourism-platform"
tail -f storage/logs/laravel.log
```

**Search for scheduler logs:**
```bash
# Windows (PowerShell)
Select-String -Path storage\logs\laravel.log -Pattern "Scheduled task"

# Linux
grep "Scheduled task" storage/logs/laravel.log
```

### Expected Log Entries

**Success:**
```
[2025-11-13 00:00:15] local.INFO: Scheduled task: bookings:update-status completed successfully
[2025-11-13 01:00:15] local.INFO: Scheduled task: plans:deactivate-expired completed successfully
```

**Failure:**
```
[2025-11-13 00:00:15] local.ERROR: Scheduled task: bookings:update-status failed
```

---

## 🔧 Troubleshooting

### Issue: "No scheduled commands are ready to run"

**Reason:** Commands only run at their scheduled times (00:00 and 01:00).

**Solution:** Test commands manually:
```bash
php artisan bookings:update-status
php artisan plans:deactivate-expired
```

---

### Issue: Commands run but don't update database

**Check:**
1. Database connection is working
2. There are bookings/plans that need updates
3. Check logs for errors

**Debug:**
```bash
# Check if there are bookings to update
php artisan tinker
> Booking::where('status', 'confirmed')->whereDate('start_date', now()->addDays(7))->count()
> exit
```

---

### Issue: Emails not sending from scheduled tasks

**Check:**
1. Email configuration in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

2. Test email manually:
```bash
php artisan tinker
> Mail::raw('Test', fn($msg) => $msg->to('test@example.com')->subject('Test'));
> exit
```

---

## 🕐 Changing Schedule Times

Edit `routes/console.php`:

```php
// Run at different time
Schedule::command('bookings:update-status')
    ->dailyAt('06:00')  // 6:00 AM instead of midnight
    ->timezone('Asia/Colombo');

// Run multiple times per day
Schedule::command('bookings:update-status')
    ->twiceDaily(9, 21)  // 9:00 AM and 9:00 PM
    ->timezone('Asia/Colombo');

// Run every hour
Schedule::command('bookings:update-status')
    ->hourly()
    ->timezone('Asia/Colombo');

// Run every 30 minutes
Schedule::command('bookings:update-status')
    ->everyThirtyMinutes()
    ->timezone('Asia/Colombo');
```

---

## 📈 Best Practices

1. **Always use timezone:** Set `->timezone('Asia/Colombo')` to avoid confusion
2. **Log everything:** Commands log to `storage/logs/laravel.log` automatically
3. **Test manually first:** Always test with `php artisan command-name` before scheduling
4. **Monitor production:** Check logs daily in first week after deployment
5. **Have backups:** Automated tasks modify data, ensure you have database backups

---

## 🚀 Production Deployment Checklist

- [ ] Cron job added to server
- [ ] Cron service is running (`service cron status`)
- [ ] Test scheduler manually (`php artisan schedule:run`)
- [ ] Check logs after first scheduled run
- [ ] Monitor for 1 week to ensure stability
- [ ] Setup log rotation (logs can get large)
- [ ] Configure log alerts (optional but recommended)

---

## 📞 Support

If scheduled tasks aren't working:

1. Check `storage/logs/laravel.log` for errors
2. Verify cron job is running (Linux) or Task Scheduler is enabled (Windows)
3. Test commands manually to isolate the issue
4. Check database has data that needs processing
5. Verify timezone settings are correct

---

**Last Updated:** November 13, 2025
**Platform:** Tourism Platform v1.0
**Laravel Version:** 11.x
