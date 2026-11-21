# Upgrade

## * → 1.3

### Bootstrap navbar: one HTML field per position

The single HTML field with a position select is replaced by four HTML fields, one per position; existing
content is migrated. Customized `mod_bootstrap_navbar` templates keep rendering the HTML at its previous
position, but ignore the other three fields until the template is updated.
