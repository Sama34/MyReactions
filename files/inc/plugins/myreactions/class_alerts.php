<?php

/**
 * MyReactions 0.0.4

 * Copyright 2017 Matthew Rogowski

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at

 ** http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.

 * Idea inspired by https://facepunch.com/ and more recently Slack

 * Twitter Emoji licenced under CC-BY 4.0
 * http://twitter.github.io/twemoji/
 * https://github.com/twitter/twemoji
 **/

declare(strict_types=1);

class MyReactionsMyAlertsFormatter extends MybbStuff_MyAlerts_Formatter_AbstractFormatter
{
    public function init(): bool
    {
        global $lang;

        $lang->load('myreactions');

        return true;
    }

    /**
     * Format an alert into it's output string to be used in both the main alerts listing page and the popup.
     *
     * @param MybbStuff_MyAlerts_Entity_Alert $alert The alert to format.
     *
     * @return string The formatted alert string.
     */
    public function formatAlert(MybbStuff_MyAlerts_Entity_Alert $alert, array $outputAlert): string
    {
        $Details = $alert->toArray();

        $from_user_data = get_user($Details['from_user_id']);

        return $this->lang->sprintf(
            $this->lang->myreactions_myalerts_myreactions_received_reaction,
            htmlspecialchars_uni($from_user_data['username'] ?? ''),
        );
    }

    /**
     * Build a link to an alert's content so that the system can redirect to it.
     *
     * @param MybbStuff_MyAlerts_Entity_Alert $alert The alert to build the link for.
     *
     * @return string The built alert, preferably an absolute link.
     */
    public function buildShowLink(MybbStuff_MyAlerts_Entity_Alert $alert): string
    {
        global $db, $settings;

        $Details = $alert->toArray();

        $post_reaction_id = (int)$Details['object_id'];

        $query = $db->simple_select('post_reactions', 'post_reaction_pid', "post_reaction_id='{$post_reaction_id}'");

        $post_reaction_pid = $db->fetch_field($query, 'post_reaction_pid') ?? 0;

        return $settings['bburl'] . '/' . get_post_link($post_reaction_pid) . '#pid' . $post_reaction_pid;
    }
}