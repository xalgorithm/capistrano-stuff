### This file contains project-specific settings ###
### See config/deploy/{stage}.rb for stage-specific settings ###

# The project name (something that capistrano can call this)
set :application, "__name__"
set :repository, "ssh://git@git.aristotle.net/__repo_name__"
# List the Drupal multi-site folders.  Use "default" if no multi-sites are installed.
set :domains, ["default"]

#=========================================================
# The following usually doesn't change for our deployments
#=========================================================

# --------------------------------------------
# Server
# --------------------------------------------
set :use_sudo, true
role :web, "gaia.aristotle.net" #the webserver's hostname

# --------------------------------------------
# General
# --------------------------------------------
set :group_writable, true
set :keep_releases, 4 

# Multistage support - see config/deploy/[STAGE].rb for specific configs
set :default_stage, "production"
set :stages, %w(production staging)

# --------------------------------------------
# Repository
# --------------------------------------------
set :scm, :git
set :deploy_via, :remote_cache
set :copy_exclude, [".svn", ".git"]

# --------------------------------------------
# SSH
# --------------------------------------------
default_run_options[:pty] = true
ssh_options[:forward_agent] = false
ssh_options[:port] = 22

# --------------------------------------------
# Set up actions
# --------------------------------------------
after 'deploy:setup', 'drupal:setup' # Here we setup the shared files directory
after 'deploy:update_code', 'aristotle:update_code'
after 'deploy:symlink', 'drupal:symlink' # After symlinking the code we symlink the shared dirs
after 'deploy:finalize_update', 'aristotle:permissions:fix'

namespace :drupal do
  task :setup, :except => { :no_release => true } do
    #create user if necessary
    run "#{sudo} id #{application} || #{sudo} /usr/sbin/useradd #{application} -d /dev/null -s /usr/bin/nologin"

    #create directories
    readable_dirs = [deploy_to, releases_path, shared_path]
    writable_dirs = []
    writable_dirs += [shared_path + "/tmp"]
    domains.each do |domain|
      writable_dirs += [shared_path + "/#{domain}/files"]
    end
    dirs = readable_dirs + writable_dirs
    dirs += %w(system).map { |d| File.join(shared_path, d) }
    # ignore errors on mkdir (added "; true" to the end) so that we can run setup multiple times
    run "#{sudo} mkdir -p #{dirs.join(' ')}; true"
    run "#{sudo} chmod -R 775 #{dirs.join(' ')}"
    run "#{sudo} chown -R #{application}:users #{readable_dirs.join(' ')}"
    run "#{sudo} chown -R apache:users #{writable_dirs.join(' ')}"
  end

  task :symlink, :except => { :no_release => true } do
    domains.each do |domain|
      # remove any link or directory that was exported from SCM, and link to remote Drupal filesystem
      run "rm -rf #{release_path}/drupal/sites/#{domain}/files"
      run "ln -nfs #{deploy_to}/#{shared_dir}/#{domain}/files #{release_path}/drupal/sites/#{domain}/files"
    end
  end
end

namespace :aristotle do
  namespace :permissions do
    task :fix, :except => { :no_release => true } do
      run "#{sudo} chown -R #{application}:users #{latest_release}"
    end
  end
  task :update_code, :except => { :no_release => true } do
    # make cached-copy group writable after update. This allows all group users to deploy using git.
	run "#{sudo} chmod -R g+w #{shared_path}/cached-copy";
  end
end
