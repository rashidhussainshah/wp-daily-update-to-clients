<?php

namespace App\Helpers;

class MotivationalQuotes
{
    /**
     * Collection of motivational quotes
     */
    protected static $quotes = [
        "Success is not final, failure is not fatal: it is the courage to continue that counts. - Winston Churchill",
        "The only way to do great work is to love what you do. - Steve Jobs",
        "Innovation distinguishes between a leader and a follower. - Steve Jobs",
        "Your work is going to fill a large part of your life, and the only way to be truly satisfied is to do what you believe is great work. - Steve Jobs",
        "The future belongs to those who believe in the beauty of their dreams. - Eleanor Roosevelt",
        "Believe you can and you're halfway there. - Theodore Roosevelt",
        "The only limit to our realization of tomorrow will be our doubts of today. - Franklin D. Roosevelt",
        "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
        "The way to get started is to quit talking and begin doing. - Walt Disney",
        "Everything you've ever wanted is on the other side of fear. - George Addair",
        "Success usually comes to those who are too busy to be looking for it. - Henry David Thoreau",
        "Don't be afraid to give up the good to go for the great. - John D. Rockefeller",
        "I find that the harder I work, the more luck I seem to have. - Thomas Jefferson",
        "Success is not the key to happiness. Happiness is the key to success. - Albert Schweitzer",
        "The only impossible journey is the one you never begin. - Tony Robbins",
        "Opportunities don't happen. You create them. - Chris Grosser",
        "Try not to become a man of success. Rather become a man of value. - Albert Einstein",
        "Great things never come from comfort zones. - Anonymous",
        "Dream bigger. Do bigger. - Anonymous",
        "Success doesn't just find you. You have to go out and get it. - Anonymous",
        "The harder you work for something, the greater you'll feel when you achieve it. - Anonymous",
        "Dream it. Wish it. Do it. - Anonymous",
        "Don't stop when you're tired. Stop when you're done. - Anonymous",
        "Wake up with determination. Go to bed with satisfaction. - Anonymous",
        "Do something today that your future self will thank you for. - Anonymous",
        "Little things make big days. - Anonymous",
        "It's going to be hard, but hard does not mean impossible. - Anonymous",
        "Don't wait for opportunity. Create it. - Anonymous",
        "Sometimes we're tested not to show our weaknesses, but to discover our strengths. - Anonymous",
        "The key to success is to focus on goals, not obstacles. - Anonymous",
    ];

    /**
     * Get a random motivational quote
     *
     * @return string
     */
    public static function random(): string
    {
        return self::$quotes[array_rand(self::$quotes)];
    }

    /**
     * Get all quotes
     *
     * @return array
     */
    public static function all(): array
    {
        return self::$quotes;
    }
}
