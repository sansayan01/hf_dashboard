# OpenClaw Integration Guide

This chatbot now supports OpenClaw as a backend AI service. If OpenClaw is configured, it will use it; otherwise it falls back to OpenRouter.

## Steps to Set Up OpenClaw on Your Server

### 1. Install Node.js and OpenClaw on the server

```bash
# SSH into your server
ssh youruser@dashboard.hfburdwan.in

# Install Node.js 22+ (Ubuntu example)
sudo apt update
sudo apt install -y nodejs npm

# Verify
node --version  # should be 22+
npm --version
```

### 2. Install OpenClaw globally

```bash
sudo npm install -g openclaw@latest
```

### 3. Create a dedicated user (optional but recommended)

```bash
sudo useradd -r -s /bin/bash openclaw
sudo mkdir -p /home/openclaw
sudo chown openclaw:openclaw /home/openclaw
sudo su - openclaw
```

### 4. Initialize OpenClaw config

```bash
openclaw init
# This creates ~/.openclaw/openclaw.json
```

Edit `~/.openclaw/openclaw.json`:

```json
{
  "env": {
    "HF_DB_HOST": "localhost",
    "HF_DB_USER": "your_db_user",
    "HF_DB_PASSWORD": "your_db_password",
    "HF_DB_NAME": "hf_database",
    "HF_DB_PORT": "3306"
  },
  "gateway": {
    "port": 18789,
    "mode": "local",
    "bind": "loopback",
    "auth": {
      "mode": "token",
      "token": "YOUR_STRONG_TOKEN_HERE"
    }
  },
  "agents": {
    "defaults": {
      "model": {
        "primary": "openrouter/stepfun/step-3.5-flash:free"
      },
      "skills": ["hf-db"]   // Optional: add HF DB skill for inventory queries
    }
  }
}
```

Generate a strong token:
```bash
openssl rand -hex 32
```

### 5. (Optional) Install the HF Database Skill

If you want OpenClaw to directly answer inventory questions, install the skill we created:

```bash
mkdir -p ~/.openclaw/skills/hf-db
# Copy the skill files (skill.js, package.json, SKILL.md) into that folder
cd ~/.openclaw/skills/hf-db
npm install
```

### 6. Create a systemd service to run OpenClaw on boot

Create `/etc/systemd/system/openclaw.service`:

```ini
[Unit]
Description=OpenClaw Gateway
After=network.target

[Service]
Type=simple
User=openclaw
Group=openclaw
WorkingDirectory=/home/openclaw
Environment="HOME=/home/openclaw"
ExecStart=/usr/bin/openclaw gateway --port=18789
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Enable and start:

```bash
sudo systemctl daemon-reload
sudo systemctl enable openclaw
sudo systemctl start openclaw
sudo systemctl status openclaw
```

### 7. Configure your Laravel app

In your `.env` file on the server, add:

```env
OPENCLAW_GATEWAY_URL=http://127.0.0.1:18789
OPENCLAW_GATEWAY_TOKEN=YOUR_STRONG_TOKEN_HERE
```

Also ensure `config/openclaw.php` exists (it's included in this repo).

### 8. Test the integration

1. Restart your Laravel app: `sudo systemctl restart nginx` or `php artisan serve`
2. Open your website's chatbot (likely at `/ai/chat` route)
3. Send a message like: "What is the stock of Amoxicillin at Haritala?"
4. If you installed the `hf-db` skill and configured it, OpenClaw should answer with real inventory data.

### 9. Security Considerations

- The OpenClaw token is stored in `.env` and never exposed to clients.
- OpenClaw runs on localhost only (`bind: loopback`) so it's not publicly accessible.
- Nginx reverse proxy is not needed because Laravel talks to OpenClaw via localhost.
- Ensure your firewall allows only local connections to port 18789.

### Troubleshooting

- **OpenClaw not running?** `sudo systemctl status openclaw`
- **Token mismatch?** Check token in Laravel `.env` matches `~/.openclaw/openclaw.json`.
- **Skill not loading?** Check OpenClaw logs: `sudo journalctl -u openclaw -f`
- **No inventory data?** Make sure the HF DB skill is installed and DB credentials are correct.
- **Still using OpenRouter?** The system checks for `OPENCLAW_GATEWAY_TOKEN` and the OpenClawService class. If either is missing, it falls back to OpenRouter.

---

That's it! Your chatbot now runs on OpenClaw.
