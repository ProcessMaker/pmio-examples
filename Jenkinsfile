#!/groovy

        properties([[$class: 'ParametersDefinitionProperty',
            parameterDefinitions: [
                [$class: 'StringParameterDefinition', defaultValue: '4.0.0.qacore.processmaker.net', description: 'Domain for PMCore installation', name : 'PMCOREHOST'],
                [$class: 'StringParameterDefinition', defaultValue: 'Default_user_key', description: 'Auth key for user Test', name : 'KEY_TEST'],
                [$class: 'StringParameterDefinition', defaultValue: 'Bob_key', description: 'Auth key for user Bob', name: 'KEY_BOB'],
                [$class: 'StringParameterDefinition', defaultValue: 'Alice_key', description: 'Auth key for user Alice', name: 'KEY_ALICE']
            ]
        ]]);

node {

    def actual_key = ''

    def deployhost = 'build-qacore.processmaker.net'

    def deploydomain = '.examples.qacore.processmaker.net'

    checkout scm

    gitCommit = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()

try {
    stage('Build') {

        if ( !fileExists ('.env') || params.KEY_TEST != 'Default user key') {
            sh """
            echo '<?php' >.env
            echo '\$host = "${params.PMCOREHOST}";' >>.env
            echo '\$key["Test"] = "${params.KEY_TEST}";' >>.env
            echo '\$key["Bob"] = "${params.KEY_BOB}";' >>.env
            echo '\$key["Alice"] = "${params.KEY_ALICE}";' >>.env

            cat .env
            """
        }
    }

    if (currentBuild.result != 'UNSTABLE') {
        stage('Deploy') {

            vhost = BRANCH_NAME.toLowerCase().replaceAll(" ", "-");
            deploydomain = vhost + deploydomain;

            def dot_env = sh(script: "cat .env |base64 -w0", returnStdout: true).trim();
            actual_key = sh(script: "cat .env |grep Test | grep -oP '= \"\\K[^\"]+(?=\")'", returnStdout: true).trim();
            echo "actual key: " + actual_key

            def ret = sh(script: "ssh -v $deployhost /home/jenkins/deploy_examples_branch.sh ${vhost} ${gitCommit} \"${dot_env}\"", returnStdout: true)

            currentBuild.description = "- Host: $deploydomain"
        }

        stage('Acceptance Test') {
        wrap([$class: 'AnsiColorBuildWrapper']) {

            def result = sh(script: "curl -v '${deploydomain}/?host=${params.PMCOREHOST}&Test=${actual_key}' |grep '${actual_key}'", returnStdout: true).trim();
            echo 'Key found: ' + result
            if (result == '') {
                throw new Exception('Cannot find Access Key in E2E scenario')
            }
            echo 'Status: ' + currentBuild.result
                hipchatSend (color: 'GREEN', notify: true, room: 'pm.io', textFormat: false, failOnError: false,
                message: "<img src='http://ieltsplanet.info/wp-content/uploads/avatars/11860/3135a9543009deaed32574afacdb0c53-bpthumb.png' width=50 height=50 align='left'>$env.JOB_NAME [#${env.BUILD_NUMBER}] - SUCCESS (<a href='${env.BUILD_URL}'>Open</a>)<br>Deployed to <b>$deploydomain</b>"
                )

        }
        }

    } else {
            hipchatSend (color: 'YELLOW', notify: true, room: 'pm.io', textFormat: false, failOnError: false,
                  message: "$env.JOB_NAME [#${env.BUILD_NUMBER}] - UNSTABLE (<a href='${env.BUILD_URL}'>Open</a>)"
            )
    }
} catch(error) {
        println(error.getMessage());

    currentBuild.result = "FAILED"
    hipchatSend (color: 'RED', notify: true, room: 'pm.io', textFormat: false, failOnError: false,
        message: "<img src='http://i.istockimg.com/file_thumbview_approve/86219539/3/stock-illustration-86219539-cute-cartoon-piggy.jpg' width=50 height=50 align='left'>$env.JOB_NAME [#${env.BUILD_NUMBER}] - FAILED (<a href='${env.BUILD_URL}'>Open</a>)"
    )
}
}