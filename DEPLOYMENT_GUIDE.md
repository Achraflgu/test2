# MSport E-commerce Deployment Guide

## Overview
This guide will help you deploy your PHP e-commerce project to Railway (frontend) with Neon PostgreSQL (database).

## Prerequisites
- GitHub account
- Railway account (free tier available)
- Neon account (free tier available)

## Step 1: Database Setup (Neon PostgreSQL)

### 1.1 Create Neon Account
1. Go to [Neon Console](https://console.neon.tech/)
2. Sign up with GitHub
3. Create a new project named "msport-ecommerce"

### 1.2 Get Database Connection Details
1. In your Neon dashboard, go to "Connection Details"
2. Copy the connection string (it looks like: `postgresql://username:password@host/database?sslmode=require`)
3. Note down the individual components:
   - Host
   - Database name
   - Username
   - Password
   - Port (usually 5432)

### 1.3 Import Your Database
1. Export your current MySQL database:
   ```bash
   mysqldump -u root -p msport > msport_backup.sql
   ```

2. Convert and import to PostgreSQL:
   - Use the migration script: `migrate_to_postgres.php`
   - Or manually create tables using the schema in the migration script

## Step 2: Railway Deployment

### 2.1 Prepare Your Repository
1. Push your code to GitHub
2. Make sure all the deployment files are included:
   - `railway.json`
   - `nixpacks.toml`
   - `composer.json`
   - `db_connection_prod.php`

### 2.2 Deploy to Railway
1. Go to [Railway](https://railway.app/)
2. Sign up with GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Select your repository
5. Railway will automatically detect it's a PHP project

### 2.3 Configure Environment Variables
In Railway dashboard, add these environment variables:

```
DATABASE_URL=postgresql://username:password@host:port/database?sslmode=require
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

Or set individual database variables:
```
DB_HOST=your-neon-host
DB_NAME=your-neon-database
DB_USER=your-neon-username
DB_PASSWORD=your-neon-password
DB_PORT=5432
```

### 2.4 Update Database Connection
1. Replace `include("db_connection.php");` with `include("db_connection_prod.php");` in your files
2. Or update the existing `db_connection.php` to use environment variables

## Step 3: File Uploads Configuration

### 3.1 Static File Storage
For production, consider using:
- **Railway Volumes** (for persistent file storage)
- **Cloudinary** (for image hosting)
- **AWS S3** (for file storage)

### 3.2 Update File Paths
Update your file upload paths to work with the new environment:
```php
// In your upload handling code
$uploadPath = getenv('RAILWAY_VOLUME_MOUNT_PATH') ?: 'admin/uploads/';
```

## Step 4: Testing and Optimization

### 4.1 Test Your Deployment
1. Visit your Railway app URL
2. Test all major functionality:
   - User registration/login
   - Product browsing
   - Shopping cart
   - Checkout process
   - Admin panel

### 4.2 Performance Optimization
1. Enable PHP OPcache
2. Optimize images
3. Use CDN for static assets
4. Implement caching

## Step 5: Domain and SSL

### 5.1 Custom Domain (Optional)
1. In Railway dashboard, go to "Settings" → "Domains"
2. Add your custom domain
3. Update DNS records as instructed

### 5.2 SSL Certificate
Railway automatically provides SSL certificates for all deployments.

## Troubleshooting

### Common Issues:

1. **Database Connection Errors**
   - Check environment variables
   - Verify database credentials
   - Ensure database is accessible from Railway

2. **File Upload Issues**
   - Check file permissions
   - Verify upload directory exists
   - Check file size limits

3. **PHP Errors**
   - Check Railway logs
   - Enable error reporting in development
   - Verify PHP extensions are installed

### Useful Commands:
```bash
# Check Railway logs
railway logs

# Connect to Railway shell
railway shell

# Check environment variables
railway variables
```

## Cost Estimation

### Free Tier Limits:
- **Railway**: $5 credit monthly (usually enough for small projects)
- **Neon**: 0.5GB storage, 10GB transfer monthly

### Paid Plans (if needed):
- **Railway Pro**: $20/month
- **Neon Pro**: $19/month

## Security Considerations

1. **Environment Variables**: Never commit sensitive data to Git
2. **Database Security**: Use strong passwords and SSL connections
3. **File Uploads**: Validate file types and sizes
4. **Admin Access**: Use strong passwords for admin accounts

## Monitoring and Maintenance

1. **Logs**: Monitor Railway logs regularly
2. **Database**: Monitor Neon dashboard for usage
3. **Backups**: Set up regular database backups
4. **Updates**: Keep dependencies updated

## Support Resources

- [Railway Documentation](https://docs.railway.app/)
- [Neon Documentation](https://neon.tech/docs)
- [PHP Deployment Best Practices](https://www.php.net/manual/en/features.commandline.webserver.php)

---

**Note**: This deployment setup is optimized for portfolio/demo purposes. For production e-commerce, consider additional security measures, monitoring, and backup strategies.
