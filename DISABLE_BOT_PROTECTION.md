# How to Disable Bot Protection on InfinityFree

## Method 1: InfinityFree Control Panel (Recommended)

### Steps:

1. **Log in to InfinityFree Client Area**
   - Go to: https://app.infinityfree.net/
   - Enter your account credentials

2. **Access Your Hosting Account**
   - Click on your hosting account (salaryplanner.infinityfree.me)
   - Click on "Control Panel" button

3. **Check for Bot Protection Settings**
   - Look for "Security" or "Protection" section
   - Check for "Bot Protection", "Anti-Bot", or "DDoS Protection" settings
   - If available, disable it or whitelist your API endpoints

4. **Note**: InfinityFree free hosting may not have this option available. Bot protection is often mandatory on free plans.

## Method 2: Contact InfinityFree Support

1. **Submit a Support Ticket**
   - Go to: https://forum.infinityfree.com/
   - Create a support ticket requesting to:
     - Disable bot protection for your domain
     - OR whitelist `/api/*` routes
     - Mention you're running a mobile app API

2. **Alternative**: Post in their forum asking for API whitelisting

## Method 3: .htaccess Workaround (May Not Work)

Add this to your `.htaccess` file in the `public` folder:

```apache
<IfModule mod_rewrite.c>
    # Try to bypass bot protection for API routes
    RewriteEngine On
    
    # Set environment variable for API requests
    RewriteCond %{REQUEST_URI} ^/api/
    RewriteRule .* - [E=NOBOTCHECK:1]
    
    # Your existing rules...
</IfModule>
```

**Note**: This may not work as bot protection runs at server level before .htaccess.

## Method 4: Upgrade to Paid Hosting

If you need full control:
- Consider upgrading to InfinityFree's paid plans (if available)
- Or migrate to a hosting provider that allows API hosting:
  - **Free Options**: 000webhost, Freehostia
  - **Paid Options**: DigitalOcean, AWS, Heroku, Vercel, Railway

## Method 5: Use a Reverse Proxy (Advanced)

Set up a reverse proxy through:
- Cloudflare (free tier available)
- This may help bypass some protection

## Current Workaround in Code

Your code already includes:
- Multiple retry attempts with different parameters (`?i=1`, `?i=2`, etc.)
- Bot protection detection and handling
- User-friendly error messages

## Recommendation

**Best Solution**: Contact InfinityFree support to whitelist your API endpoints (`/api/v1/*`). This is the cleanest solution and maintains security for your website while allowing API access.

**Quick Fix**: The current code implementation with retry logic should work most of the time. If it fails, users can wait a few seconds and try again.

