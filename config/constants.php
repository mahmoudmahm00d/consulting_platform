<?php

class ApplicationRoles
{
    public static $admin = 'Admin';
    public static $specialist = 'Specialist';
    public static $user = 'User';
}

class ApplicationGender
{
    public static $male = 'Male';
    public static $female = 'Female';
    public static $available = ['Male', 'Female'];
}

class AppointmentStatus
{
    public static $waitingForPayment = 'WaitingForPayment';
    public static $missed = 'Missed';
    public static $done = 'Done';
    public static $upcoming = 'Upcoming';
    public static $available = ['WaitingForPayment', 'Missed', 'Done', 'Upcoming'];
}

class ApplicationDays
{
    public static $saturday = 'Saturday';
    public static $sunday = 'Sunday';
    public static $monday = 'Monday';
    public static $tuesday = 'Tuesday';
    public static $wednesday = 'Wednesday';
    public static $thursday = 'Thursday';
    public static $friday = 'Friday';

    public static $all = [
        'Saturday',
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
    ];
}

class TransactionTypes
{
    public static $deposit = 'Deposit';
    public static $transfer = 'Transfer';
    public static $reversal = 'Reversal';
    
    public static $all = [
        'Deposit',
        'Transfer',
        'Reversal',
    ];
}
