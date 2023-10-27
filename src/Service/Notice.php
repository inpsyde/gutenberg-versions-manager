<?php

declare(strict_types=1);

namespace Inpsyde\GutenbergVersionManager\Service;

class Notice
{
    public const WARNING = 'warning';
    public const SUCCESS = 'success';
    public const INFO = 'info';
    public const ERROR = 'error';

    private const LEVELS = [
        self::WARNING,
        self::SUCCESS,
        self::INFO,
        self::ERROR,
    ];

    public static function new(): Notice
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function addNotice(string $message, string $level): void
    {
        \add_action('admin_notices', function () use ($message, $level) {
            ?>
            <div class="notice notice-<?= \esc_attr($this->ensureLevel($level)) ?>">
                <p>
                    <?= \wp_kses_post($message) ?>
                </p>
            </div>
            <?php
        });
    }

    private function ensureLevel(string $level): string
    {
        return \in_array($level, self::LEVELS, true) ? $level : self::INFO;
    }
}
