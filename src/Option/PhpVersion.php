<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final class PhpVersion
{
    /**
     * @var int
     */
    public const ANY = -1;

    /**
     * @var string
     */
    public const CONFIG = 'compliance:configuration:php:version';

    /**
     * @var int
     */
    public const DEV = self::PHP_83;

    /**
     * @var array<int,string>
     */
    public const END_OF_LIFE_DATE = [
        self::PHP_10 => '2000-10-20',
        self::PHP_20 => '2000-10-20',
        self::PHP_30 => '2000-10-20',
        self::PHP_40 => '2001-06-23',
        self::PHP_41 => '2002-03-12',
        self::PHP_42 => '2002-09-06',
        self::PHP_43 => '2005-03-31',
        self::PHP_44 => '2008-08-07',
        self::PHP_50 => '2005-09-05',
        self::PHP_51 => '2006-08-24',
        self::PHP_52 => '2011-01-06',
        self::PHP_53 => '2014-08-14',
        self::PHP_54 => '2015-09-03',
        self::PHP_55 => '2016-07-21',
        self::PHP_56 => '2018-12-31',
        self::PHP_70 => '2019-01-10',
        self::PHP_71 => '2019-12-01',
        self::PHP_72 => '2020-11-30',
        self::PHP_73 => '2021-12-06',
        self::PHP_74 => '2022-11-28',
        self::PHP_80 => '2023-11-26',
        self::PHP_81 => '2024-11-25',
        self::PHP_82 => '2025-12-01',
        self::PHP_83 => '9999-12-01',
        self::PHP_84 => '9999-12-01',
        self::PHP_90 => '9999-12-01',
        self::ANY => '9999-12-01',
    ];

    /**
     * @var int
     */
    public const LATEST = self::PHP_82;

    /**
     * @var int
     */
    public const PHP_10 = 10000;

    /**
     * @var int
     */
    public const PHP_20 = 20000;

    /**
     * @var int
     */
    public const PHP_30 = 30000;

    /**
     * @var int
     */
    public const PHP_40 = 40000;

    /**
     * @var int
     */
    public const PHP_41 = 40100;

    /**
     * @var int
     */
    public const PHP_42 = 40200;

    /**
     * @var int
     */
    public const PHP_43 = 40300;

    /**
     * @var int
     */
    public const PHP_44 = 40400;

    /**
     * @var int
     */
    public const PHP_50 = 50000;

    /**
     * @var int
     */
    public const PHP_51 = 50100;

    /**
     * @var int
     */
    public const PHP_52 = 50200;

    /**
     * @var int
     */
    public const PHP_53 = 50300;

    /**
     * @var int
     */
    public const PHP_54 = 50400;

    /**
     * @var int
     */
    public const PHP_55 = 50500;

    /**
     * @var int
     */
    public const PHP_56 = 50600;

    /**
     * @var int
     */
    public const PHP_70 = 70000;

    /**
     * @var int
     */
    public const PHP_71 = 70100;

    /**
     * @var int
     */
    public const PHP_72 = 70200;

    /**
     * @var int
     */
    public const PHP_73 = 70300;

    /**
     * @var int
     */
    public const PHP_74 = 70400;

    /**
     * @var int
     */
    public const PHP_80 = 80000;

    /**
     * @var int
     */
    public const PHP_81 = 80100;

    /**
     * @var int
     */
    public const PHP_82 = 80200;

    /**
     * @var int
     */
    public const PHP_83 = 80300;

    /**
     * @var int
     */
    public const PHP_84 = 80400;

    /**
     * @var int
     */
    public const PHP_90 = 90000;

    /**
     * @var array<int,string>
     */
    public const RELEASE_DATE = [
        self::PHP_10 => '1995-06-08',
        self::PHP_20 => '1997-11-01',
        self::PHP_30 => '1998-06-06',
        self::PHP_40 => '2000-05-22',
        self::PHP_41 => '2001-12-10',
        self::PHP_42 => '2002-04-22',
        self::PHP_43 => '2002-12-27',
        self::PHP_44 => '2005-07-11',
        self::PHP_50 => '2004-07-13',
        self::PHP_51 => '2005-11-24',
        self::PHP_52 => '2006-11-02',
        self::PHP_53 => '2009-06-30',
        self::PHP_54 => '2012-03-01',
        self::PHP_55 => '2013-06-20',
        self::PHP_56 => '2014-08-28',
        self::PHP_70 => '2015-12-03',
        self::PHP_71 => '2016-12-01',
        self::PHP_72 => '2017-11-30',
        self::PHP_73 => '2018-12-06',
        self::PHP_74 => '2019-11-28',
        self::PHP_80 => '2020-11-26',
        self::PHP_81 => '2021-11-25',
        self::PHP_82 => '2022-12-01',
        self::PHP_83 => '9999-12-01',
        self::PHP_84 => '9999-12-01',
        self::PHP_90 => '9999-12-01',
        self::ANY => '9999-12-01',
    ];

    /**
     * @var int
     */
    public const STABLE = self::PHP_81;

    /**
     * @var list<int>
     */
    public const SUPPORTED = [
        self::PHP_72,
        self::PHP_73,
        self::PHP_74,
        self::PHP_80,
        self::PHP_81,
        self::PHP_82,
        self::PHP_83,
        self::PHP_84,
    ];

    /**
     * @var array<int,string>
     */
    public const TO_STRING = [
        self::PHP_10 => '1.0',
        self::PHP_20 => '2.0',
        self::PHP_30 => '3.0',
        self::PHP_40 => '4.0',
        self::PHP_41 => '4.1',
        self::PHP_42 => '4.2',
        self::PHP_43 => '4.3',
        self::PHP_44 => '4.4',
        self::PHP_50 => '5.0',
        self::PHP_51 => '5.1',
        self::PHP_52 => '5.2',
        self::PHP_53 => '5.3',
        self::PHP_54 => '5.4',
        self::PHP_55 => '5.5',
        self::PHP_56 => '5.6',
        self::PHP_70 => '7.0',
        self::PHP_71 => '7.1',
        self::PHP_72 => '7.2',
        self::PHP_73 => '7.3',
        self::PHP_74 => '7.4',
        self::PHP_80 => '8.0',
        self::PHP_81 => '8.1',
        self::PHP_82 => '8.2',
        self::PHP_83 => '8.3',
        self::PHP_84 => '8.4',
        self::PHP_90 => '9.0',
        self::ANY => '*',
    ];
}
