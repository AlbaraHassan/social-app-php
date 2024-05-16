<?php

const INT = 'INT';
const NOTNULL = 'NOT NULL';
const TEXT = 'TEXT';
const DCT = "DEFAULT CURRENT_TIMESTAMP";
const DESC = "DESC";
const TIMESTAMP = 'TIMESTAMP';
const FLOAT = 'FLOAT';
const DOUBLE = 'DOUBLE';
const DECIMAL = 'DECIMAL';
const DATE = 'DATE';
const TIME = 'TIME';
const DATETIME = 'DATETIME';

function equals(string $val1, string $val2): string
{
    return "$val1 = $val2";
}
function alias(string $name,string $alias): string
    {
        return "$name as $alias";
    }
function VARCHAR(int $size): string
{
    return "VARCHAR($size)";
}

function CNT(string $count = '*'): string
{
    return "COUNT($count)";
}
