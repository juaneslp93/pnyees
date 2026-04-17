<?php

abstract class Migration
{
    abstract public function up(mysqli $db): void;
    abstract public function down(mysqli $db): void;
}
