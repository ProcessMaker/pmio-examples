#!/groovy
node {

    def clientid = ''
    def clientsecret = ''
    def username = ''
    def password = ''
    def token = ''

    def deployhost = 'build-qacore.processmaker.net'

    def deploydomain = '.examples.qacore.processmaker.net'

    // be positive =)
    currentBuild.result = 'SUCCESS'

    checkout scm

    gitCommit = sh(returnStdout: true, script: 'git rev-parse HEAD').trim()

try {
    stage('Build') {
      lock(resource: "lock_${env.NODE_NAME}_${env.BRANCH_NAME}", inversePrecedence: true) {

            sh """
            echo '<?php' >.env
            echo '\$host = \'4.0.0.qacore.processmaker.net\';' >>.env
            echo '\$key[\'Test\'] = \'Default user key\';' >>.env
            echo '\$key[\'Bob\'] = \'Bob key\';' >>.env
            echo '\$key[\'Alice\'] = \'Alice key\';' >>.env

            cat .env
            """
        }
    }
    stage('Test') {
        wrap([$class: 'AnsiColorBuildWrapper']) {

           echo 'Status: ' + currentBuild.result
        }
    }

    if (currentBuild.result == 'SUCCESS') {
        stage('Deploy') {

            vhost = BRANCH_NAME.toLowerCase().replaceAll(" ", "-");
            deploydomain = vhost + deploydomain;

            def dot_env = sh "cat .env |base64 -w0";

            def ret = sh(script: "ssh -v $deployhost /home/jenkins/deploy_examples_branch.sh ${vhost} ${gitCommit} \"${dot_env}\"", returnStdout: true)

            currentBuild.description = "- Host: $deploydomain"
        }
    } else {
            //hipchatSend (color: 'YELLOW', notify: true, room: 'ProcessMaker Core', textFormat: false, failOnError: false,
            //      message: "$env.JOB_NAME [#${env.BUILD_NUMBER}] - ${currentBuild.result} (<a href='${env.BUILD_URL}'>Open</a>)"
            //)
    }
} catch(error) {
    currentBuild.result = "FAILED"
//    hipchatSend (color: 'RED', notify: true, room: 'ProcessMaker Core', textFormat: false, failOnError: false,
//        message: "<img src='http://i.istockimg.com/file_thumbview_approve/86219539/3/stock-illustration-86219539-cute-cartoon-piggy.jpg' width=50 height=50 align='left'>$env.JOB_NAME [#${env.BUILD_NUMBER}] - ${currentBuild.result} (<a href='${env.BUILD_URL}'>Open</a>)"
//    )
}
}