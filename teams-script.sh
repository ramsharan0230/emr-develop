#!/bin/bash
set -euo pipefail
FAILURE=1
SUCCESS=0
TEAMSWEBHOOKURL="https://cogenthealth.webhook.office.com/webhookb2/eb06d446-93df-4b16-83c7-58b3e4f48617@4d4f6633-e7d2-4238-932d-afff9e997984/IncomingWebhook/33d47e9fcf874e79870884cb366426d4/5b899d19-f111-45dd-afd5-e0190bbae4ee"
function print_slack_summary_deploy() {
local slack_msg_header
    local slack_msg_body
    local slack_channel
# Populate header and define slack channels
slack_msg_header=":x: *Deploy to ${ENVIRONMENTNAME} failed*"
if [[ "${EXIT_STATUS}" == "${SUCCESS}" ]]; then
        slack_msg_header=":heavy_check_mark: *Deploy to ${ENVIRONMENTNAME} succeeded*"
        #slack_channel="$CHANNEL_TEST"
    fi
cat <<-TEAMS
            {
                "blocks": [
                    {
                        "type": "section",
                        "text": {
                            "type": "mrkdwn",
                            "text": "${slack_msg_header}"
                        }
                    },
                    {
                        "type": "divider"
                    },
                    {
                        "type": "section",
                        "fields": [
                            {
                                "type": "mrkdwn",
                                "text": "*Stage:*\nDeploy"
                            },
                            {
                                "type": "mrkdwn",
                                "text": "*Pushed By:*\n${GITLAB_USER_NAME}"
                            },
                            {
                                "type": "mrkdwn",
                                "text": "*Job URL:*\nGITLAB_REPO_URL/${CI_JOB_ID}"
                            },
                            {
                                "type": "mrkdwn",
                                "text": "*Commit URL:*\nGITLAB_REPO_URL$(git rev-parse HEAD)"
                            },
                            {
                                "type": "mrkdwn",
                                "text": "*Commit Branch:*\n${CI_COMMIT_REF_NAME}"
                            }
                        ]
                    },
                    {
                        "type": "divider"
                    }
                ]
}
TEAMS
}
function share_slack_update_deploy() {
local teams_webhook
teams_webhook="$TEAMSWEBHOOKURL"
curl                                         \
        --data-urlencode "payload=$(print_slack_summary_deploy)"  \
        "${teams_webhook}"
}
