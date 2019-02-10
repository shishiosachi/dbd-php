<?php
/*************************************************************************************
 *   MIT License                                                                     *
 *                                                                                   *
 *   Copyright (C) 2009-2017 by Nurlan Mukhanov <nurike@gmail.com>                   *
 *                                                                                   *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy    *
 *   of this software and associated documentation files (the "Software"), to deal   *
 *   in the Software without restriction, including without limitation the rights    *
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell       *
 *   copies of the Software, and to permit persons to whom the Software is           *
 *   furnished to do so, subject to the following conditions:                        *
 *                                                                                   *
 *   The above copyright notice and this permission notice shall be included in all  *
 *   copies or substantial portions of the Software.                                 *
 *                                                                                   *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR      *
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,        *
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE     *
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER          *
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,   *
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE   *
 *   SOFTWARE.                                                                       *
 ************************************************************************************/

namespace DBD\Base;

final class DBDHelper
{
    /**
     *
     * @param string $statement
     *
     * @return string
     */
    public final static function cleanSql($statement) {
        $array = preg_split('/\R/u', $statement);

        foreach($array as $idx => $line) {
            //$array[$idx] = trim($array[$idx], "\s\t\n\r");
            if(!$array[$idx] || preg_match('/^[\s\R\t]*?$/u', $array[$idx])) {
                unset($array[$idx]);
                continue;
            }
            if(preg_match('/^\s*?(UNION|CREATE|DELETE|UPDATE|SELECT|FROM|WHERE|JOIN|LIMIT|OFFSET|ORDER|GROUP)/i', $array[$idx])) {
                $array[$idx] = ltrim($array[$idx]);
            }
            else {
                $array[$idx] = "    " . ltrim($array[$idx]);
            }
        }

        return implode("\n", $array);
    }

    /**
     * @param int|float $cost
     *
     * @return int
     */
    public final static function debugMark($cost) {
        switch(true) {
            case ($cost >= 0 && $cost <= 20):
                return 1;
            case ($cost >= 21 && $cost <= 50):
                return 2;
            case ($cost >= 51 && $cost <= 90):
                return 3;
            case ($cost >= 91 && $cost <= 140):
                return 4;
            case ($cost >= 141 && $cost <= 200):
                return 5;
            default:
                return 6;
        }
    }

    /**
     * @param $ARGS
     *
     * @return array
     */
    public final static function prepareArgs($ARGS) {
        // Shift query from passed arguments. Query is always first
        $statement = array_shift($ARGS);
        // Build array of arguments
        $args = self::parseArgs($ARGS);

        return [
            $statement,
            $args,
        ];
    }

    /**
     * @param $ARGS
     *
     * @return array
     */
    public final static function parseArgs($ARGS) {
        $args = [];

        foreach($ARGS as $arg) {
            if(is_array($arg)) {
                foreach($arg as $subArg) {
                    $args[] = $subArg;
                }
            }
            else {
                $args[] = $arg;
            }
        }

        return $args;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public final static function compileInsertArgs($data) {

        $columns = "";
        $values = "";
        $args = [];

        foreach($data as $c => $v) {
            $pattern = "/[^\"a-zA-Z0-9_-]/";
            $c = preg_replace($pattern, "", $c);
            $columns .= "$c, ";
            $values .= "?,";
            if($v === true) {
                $v = 'true';
            }
            if($v === false) {
                $v = 'false';
            }
            $args[] = $v;
        }

        $columns = preg_replace("/, $/", "", $columns);
        $values = preg_replace("/,$/", "", $values);

        return [
            'COLUMNS' => $columns,
            'VALUES'  => $values,
            'ARGS'    => $args,
        ];
    }

    public final static function compileUpdateArgs($data) {
        $columns = "";
        $args = [];

        $pattern = "/[^\"a-zA-Z0-9_-]/";
        foreach($data as $k => $v) {
            $k = preg_replace($pattern, "", $k);
            $columns .= "$k = ?, ";
            $args[] = $v;
        }

        $columns = preg_replace("/, $/", "", $columns);

        return [
            'COLUMNS' => $columns,
            'ARGS'    => $args,
        ];
    }
}