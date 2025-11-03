<?php

namespace App\Helpers;

class MotivationalQuotes
{
    /**
     * Collection of motivational quotes for web developers
     * Focused on: teamwork, coding excellence, dedication, collaboration, and professional growth
     */
    protected static $quotes = [
        // Teamwork & Collaboration
        "Alone we can do so little; together we can do so much. - Helen Keller",
        "Teamwork makes the dream work. Great things are never done by one person, they're done by a team of people.",
        "Coming together is a beginning, staying together is progress, and working together is success. - Henry Ford",
        "Individual commitment to a group effort - that is what makes a team work, a company work, a society work. - Vince Lombardi",
        "The strength of the team is each individual member. The strength of each member is the team. - Phil Jackson",
        "Great teams do not hold back with one another. They are unafraid to air their dirty laundry. They admit their mistakes, their weaknesses, and their concerns.",
        "None of us is as smart as all of us. - Ken Blanchard",
        "Collaboration allows us to know more than we are capable of knowing by ourselves. - Paul Solarz",

        // Coding & Development Excellence
        "Code is like humor. When you have to explain it, it's bad. - Cory House",
        "First, solve the problem. Then, write the code. - John Johnson",
        "Any fool can write code that a computer can understand. Good programmers write code that humans can understand. - Martin Fowler",
        "Clean code always looks like it was written by someone who cares. - Robert C. Martin",
        "The best error message is the one that never shows up. - Thomas Fuchs",
        "Debugging is twice as hard as writing the code in the first place. Therefore, if you write the code as cleverly as possible, you are not smart enough to debug it. - Brian Kernighan",
        "Make it work, make it right, make it fast. - Kent Beck",
        "Simplicity is the soul of efficiency. - Austin Freeman",
        "The computer was born to solve problems that did not exist before. - Bill Gates",
        "Testing leads to failure, and failure leads to understanding. - Burt Rutan",

        // Dedication & Professional Growth
        "The only way to learn a new programming language is by writing programs in it. - Dennis Ritchie",
        "The best way to predict the future is to implement it. - David Heinemeier Hansson",
        "Programs must be written for people to read, and only incidentally for machines to execute. - Harold Abelson",
        "The function of good software is to make the complex appear to be simple. - Grady Booch",
        "Quality is not an act, it is a habit. - Aristotle",
        "Continuous improvement is better than delayed perfection. - Mark Twain",
        "Learning never exhausts the mind. Keep coding, keep learning, keep growing. - Leonardo da Vinci",
        "Excellence is not a skill, it's an attitude. Write code with pride.",

        // Work Dedication
        "I find that the harder I work, the more luck I seem to have. - Thomas Jefferson",
        "Don't watch the clock; do what it does. Keep going. - Sam Levenson",
        "Success usually comes to those who are too busy working to be looking for it. - Henry David Thoreau",
        "The only way to do great work is to love what you do. - Steve Jobs",
        "Do something today that your future self will thank you for.",
        "Little things make big days. Small commits make great projects.",
        "It's going to be hard, but hard does not mean impossible.",

        // Problem Solving & Innovation
        "Every great developer you know got there by solving problems they were unqualified to solve until they actually did it. - Patrick McKenzie",
        "The most disastrous thing that you can ever learn is your first programming language. - Alan Kay",
        "Talk is cheap. Show me the code. - Linus Torvalds",
        "Good code is its own best documentation. - Steve McConnell",
        "Don't comment bad code - rewrite it. - Brian Kernighan",
        "The best performance improvement is the transition from the nonworking state to the working state. - John Ousterhout",
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
