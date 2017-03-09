#!/groovy
node {

    properties([[$class: 'ParametersDefinitionProperty',
        parameterDefinitions: [
            [$class: 'StringParameterDefinition', defaultValue: 'Default user key', description: 'Auth key for user Test', name : 'KEY_TEST'],
            [$class: 'StringParameterDefinition', defaultValue: 'Bob key', description: 'Auth key for user Bob', name: 'KEY_BOB'],
            [$class: 'StringParameterDefinition', defaultValue: 'Alice key', description: 'Auth key for user Alice', name: 'KEY_ALICE']
        ]
    ]]);

    def actual_key = ''

    def deployhost = 'build-qacore.processmaker.net'

    def deploydomain = '.examples.qacore.processmaker.net'

    // be positive =)
    currentBuild.result = 'SUCCESS'

    checkout scm

    gitCommit = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()

try {
    stage('Build') {

        if ( !fileExists ('.env') || KEY_TEST != 'Default user key') {
            sh """
            echo '<?php' >.env
            echo '\$host = "4.0.0.qacore.processmaker.net";' >>.env
            echo '\$key["Test"] = "${KEY_TEST}";' >>.env
            echo '\$key["Bob"] = "${KEY_BOB}";' >>.env
            echo '\$key["Alice"] = "${KEY_ALICE}";' >>.env

            cat .env
            """
        }
    }

    if (currentBuild.result == 'SUCCESS') {
        stage('Deploy') {

            vhost = BRANCH_NAME.toLowerCase().replaceAll(" ", "-");
            deploydomain = vhost + deploydomain;

            def dot_env = sh(script: "cat .env |base64 -w0", returnStdout: true).trim();
            actual_key = sh(script: "cat .env |grep '\'Test\'' | grep -Eo '[\\047].*[\\047]'", returnStdout: true).trim();
            echo "actual key: " + actual_key
            def ret = sh(script: "ssh -v $deployhost /home/jenkins/deploy_examples_branch.sh ${vhost} ${gitCommit} \"${dot_env}\"", returnStdout: true)

            currentBuild.description = "- Host: $deploydomain"
        }

        stage('Acceptance Test') {
        wrap([$class: 'AnsiColorBuildWrapper']) {

            def result = sh(script: "curl -v ${deploydomain}/index.php |grep '${actual_key}'", returnStdout: true).trim();
            echo 'Key found: ' + result
            echo 'Status: ' + currentBuild.result

        }
        }

    } else {
            //hipchatSend (color: 'YELLOW', notify: true, room: 'ProcessMaker Core', textFormat: false, failOnError: false,
            //      message: "$env.JOB_NAME [#${env.BUILD_NUMBER}] - ${currentBuild.result} (<a href='${env.BUILD_URL}'>Open</a>)"
            //)
    }
} catch(error) {
        echo error
    currentBuild.result = "FAILED"
//    hipchatSend (color: 'RED', notify: true, room: 'ProcessMaker Core', textFormat: false, failOnError: false,
//        message: "<img src='http://i.istockimg.com/file_thumbview_approve/86219539/3/stock-illustration-86219539-cute-cartoon-piggy.jpg' width=50 height=50 align='left'>$env.JOB_NAME [#${env.BUILD_NUMBER}] - ${currentBuild.result} (<a href='${env.BUILD_URL}'>Open</a>)"
//    )
}
}