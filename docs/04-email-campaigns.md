# Email Campaigns

**URL:** `/admin/email-campaigns`  
**Who can access:** Rashid, Zahid, Ayub, Ali

---

## Sending a campaign (step by step)

### 1. Pick a campaign from the list
All campaigns are pre-written and ready. Click the campaign name to open it.

### 2. Send a test email first
Before sending to 66k people, always test:
```
Test Email: rashid.bukhari78600@gmail.com
Test Name:  Rashid
→ Send Test
```
Check your inbox. If it looks good, proceed.

### 3. Choose who to send to

**Option A — Send to everyone (full list)**  
Click **Dispatch to All** — sends to all 66k `homey_client` users automatically.

**Option B — Send to specific people**  
Use the "Send to Selected" dropdown:
- Type a name or email to search
- Select one or more people
- Click Send

### 4. Done
The system sends in batches and logs every delivery.

---

## Available campaigns

### Homey campaigns
| Campaign | Pitch |
|---|---|
| Channel Manager | Airbnb + Booking.com calendar sync |
| AI Auto-Reply Bot | Answer guest questions 24/7 |
| WhatsApp Notifications | Booking alerts on WhatsApp |
| Dynamic Pricing Engine | Auto-adjust nightly rates |
| SimplifyRes Sync | List once, publish everywhere |
| Virtual Tour Integration | 360° tours for listings |

### Houzez campaigns
| Campaign | Pitch |
|---|---|
| Houzi Mobile App | Branded iOS & Android app |
| Lead Automation & CRM | Auto follow-up on leads |
| AI Property Listing Writer | Write 50 listings in an hour |
| WhatsApp for Enquiries | Buyers reply 5× faster |
| Portal Listings Sync | Bayut, Zameen, Rightmove sync |
| Live Chat & AI Fallback | Never lose a visitor enquiry |

### Retainer packages
| Campaign | Pitch |
|---|---|
| Homey Maintenance Retainer | Rs 49/149/399 per month plans |
| Houzez Maintenance Retainer | Same tiers for Houzez clients |

---

## Editing a campaign

Open any campaign → click **Edit**.

The HTML editor shows raw code — **do not use a word processor to copy-paste** as it will break the formatting.

To make a text change:
1. Find the text in the editor (Ctrl+F in browser won't work — just scroll)
2. Edit directly in the dark code area
3. Click **Update Campaign**

> **Important:** Only edit text content. Do not delete `<table>`, `<tr>`, or `<td>` tags — these create the card layout.

---

## Personalisation

Use these placeholders anywhere in subject or body:

| Placeholder | Replaced with |
|---|---|
| `{{name}}` | Full name, e.g. "Ahmed Khan" |
| `{{first_name}}` | First name only, e.g. "Ahmed" |

**Example subject:**
```
{{first_name}}, your Homey site can sync with Airbnb automatically
```
Recipient sees: `Ahmed, your Homey site can sync with Airbnb automatically`

---

## Campaign status

| Status | Meaning |
|---|---|
| Draft | Ready to send, not sent yet |
| Sending | Currently dispatching |
| Sent | Completed — cannot edit |

---

## Creating a new campaign

Go to `/admin/email-campaigns/create`:
- Fill in name, subject, from name/email
- Paste or write HTML in the editor
- Set target role to `homey_client`
- Save as Draft

Or use a **Quick Template** (right panel) to load a pre-built layout.

---

## From email addresses

| Address | Use for |
|---|---|
| `sales@webpenter.com` | Rashid's campaigns |
| `zaars59208@gmail.com` | Zahid's campaigns |
