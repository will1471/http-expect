<?php

namespace Will1471\HttpExpect\TestUtil;

interface ExpectResponseInterface
{
    public function toReturnBody(string $body): void;
}
