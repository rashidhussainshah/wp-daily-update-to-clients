<?php

namespace App\Helpers;

/**
 * Webpenter-branded quotes shown on the admin login page (auth/master.blade.php)
 * only - kept separate from the general MotivationalQuotes pool used elsewhere
 * (e.g. the dashboard) so the two never mix.
 *
 * Product claims here are grounded in real docs, not invented:
 * - BookHere facts: D:\wp\bookhere\bookhere-documentation (constants/sections/faq.ts, features.ts)
 * - Real estate line: HouzezCampaignSeeder / HomeyCampaignSeeder already in this codebase
 * Stripe Connect is explicitly marked "(future)" in BookHere's own docs, so it's
 * phrased here as upcoming, not as an already-shipped feature.
 */
class WebpenterQuotes
{
    protected static $quotes = [
        // Webpenter Pride
        "We deliver good work at Webpenter.",
        "We test things before deployment. - Webpenter",
        "At Webpenter, quality is not negotiable.",
        "We don't just write code, we build trust. - Webpenter",
        "Webpenter: where clean code meets real business results.",
        "Real estate expert. - Webpenter",

        // BookHere
        "BookHere is the best booking and rental complete end-to-end solution. - Webpenter",
        "BookHere: a complete, production-ready React Native app for property rentals and bookings - built for guests and hosts alike. - Webpenter",
        "iOS, Android, guests, hosts - BookHere covers the whole booking experience. - Webpenter",
        "BookHere hosts manage listings, pricing, and availability with ease. - Webpenter",
        "One tap to approve or decline a booking request - that's BookHere for hosts. - Webpenter",
        "Guests pay easily with multiple payment methods and instant invoices on BookHere. - Webpenter",
        "Request to Book: guests reserve now, hosts confirm within 24 hours. - Webpenter",
        "BookHere admins get complete, system-wide visibility into every booking. - Webpenter",
        "Hosts and guests chat in real time on BookHere, with push notifications the moment a message lands. - Webpenter",
        "Stripe Connect is coming to BookHere, for faster, direct host payouts. - Webpenter",
        "Hotels, vehicles, equipment, vacation homes - BookHere handles every kind of rental. - Webpenter",
        "BookHere: the booking and rental app built for your business. - Webpenter",
        "A dedicated support team stands behind every BookHere deployment. - Webpenter",
    ];

    /**
     * Get a random Webpenter quote.
     *
     * @return string
     */
    public static function random(): string
    {
        return self::$quotes[array_rand(self::$quotes)];
    }

    /**
     * Get all quotes.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::$quotes;
    }
}
