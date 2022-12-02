# server-based syntax
# ======================
# Defines a single server with a list of roles and multiple properties.
# You can define all roles on a single server, or split them:

server 'oweb11:22', user: 'root', roles: %w(db)
server 'oweb12:22', user: 'root'
server 'oweb13:22', user: 'root'
server 'oweb14:22', user: 'root'
# server "example.com", user: "deploy", roles: %w{app web}, other_property: :other_value
# server "db.example.com", user: "deploy", roles: %w{db}

set :stage, :production
set :branch, "master"