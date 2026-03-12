node {
  checkout scm

  stage("Build") {
    sh 'php -v'
    sh 'composer --version'
    sh 'composer install'
  }

  stage("Testing") {
    sh 'echo "Ini adalah test"'
  }
}