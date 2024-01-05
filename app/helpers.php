<?php

if (!function_exists('get_vote_class')) {
    function get_vote_class(int $vote)
    {
        return 'vote-' . $vote;
    }
}
