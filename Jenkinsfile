node {
    checkout scm

    // Build stage
    stage('Build') {
        docker.image('composer:2').inside('--entrypoint="" -u root') {
            sh '''
                git config --global --add safe.directory "$WORKSPACE"
                composer install --no-interaction --prefer-dist --optimize-autoloader
            '''
        }
    }

    // Testing stage
    stage('Testing') {
        docker.image('composer:2').inside('--entrypoint="" -u root') {
            sh '''
                if [ ! -f .env ]; then
                    if [ -f .env.example ]; then
                        cp .env.example .env
                    else
                        printf "APP_ENV=testing\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\nDB_CONNECTION=sqlite\nCACHE_STORE=array\nQUEUE_CONNECTION=sync\nSESSION_DRIVER=array\n" > .env
                    fi
                fi
                touch database/database.sqlite
                php artisan test
            '''
        }
    }

    // Frontend assets for production
    stage('Assets') {
        docker.image('node:22-alpine').inside('--entrypoint="" -u root') {
            sh '''
                npm ci --no-audit --no-fund
                npm run build
            '''
        }
    }

    // Strip dev PHP packages before deploy
    stage('Prepare Production') {
        docker.image('composer:2').inside('--entrypoint="" -u root') {
            sh '''
                composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
            '''
        }
    }

    // Deploy to production stage
    stage('Deploy') {
        docker.image('instrumentisto/rsync-ssh:alpine').inside('--entrypoint="" -u root') {
            withCredentials([sshUserPrivateKey(credentialsId: 'production', keyFileVariable: 'SSH_KEY', usernameVariable: 'SSH_USER')]) {
                sh '''
                    APP_DIR="/home/$SSH_USER/prod.kelasdevops.xyz"
                    mkdir -p ~/.ssh
                    chmod 700 ~/.ssh
                    ssh-keyscan -H "$PROD_HOST" > ~/.ssh/known_hosts
                    chmod 600 "$SSH_KEY" ~/.ssh/known_hosts
                    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=yes "$SSH_USER@$PROD_HOST" "mkdir -p $APP_DIR"
                    rsync -rv --delete -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=yes" ./ "$SSH_USER@$PROD_HOST:$APP_DIR/" --exclude=.env --exclude=storage --exclude=.git --exclude=node_modules --exclude=tests --exclude=Jenkinsfile
                    ssh -i "$SSH_KEY" -o StrictHostKeyChecking=yes "$SSH_USER@$PROD_HOST" "sh $APP_DIR/deploy/remote-bootstrap.sh $APP_DIR"
                '''
            }
        }
    }
}