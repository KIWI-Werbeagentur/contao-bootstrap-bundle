# KIWI. Contao Bootstrap

**Implement the Bootstrap CSS framework into your Contao CMS.**

## Table of contents
1. [Scope](#scope)
2. [Setup](#setup)
   1. [Installation](#installation)
   2. [Implementation](#implementation)
   3. [Widget](#widget)

## Scope <a name="scope"></a>

This bundle allows you to define bootstrap layout properties within your Contao backend.

Define **container widths and flex properties** for wrapping elements:
- articles (tl_article)
- element groups (tl_content)
- fieldsets (tl_form_field)
- forms (tl_form)
- sections (tl_layout)

Set **colspans and flex properties** for components:
- content elements (tl_content)
- form fields (tl_form_field)
- modules and their list items (tl_module)

Create responsive fields yourself by using the integrated **ResponsiveWidget** or **OptionalResponsiveWidget**.


## Setup <a name="setup"></a>

### Installation <a name="installation"></a>
Install the bundle via composer
 ```
composer require kiwi/contao-bootstrap-bundle
 ```

### Implementation <a name="implementation"></a>
**Step 1: (Re-)store themes**

Go to themes (<em>/contao?do=themes</em>) and create or edit a theme. Choose the bootstrap that shall be loaded or chose none to load all – in any case you need to restore the theme. 


**Step 2: (Re-)store layouts**

Afterwards you go to layouts (<em>/contao?do=themes&table=tl_layout&id={{theme_id}}</em>) and create or edit one. Define container widths for your given row sections (header, footer). 
If you need to setup sidebars you are able to define the container width for the wrapping element they're placed in. Next determine how many colspans the sidebars shall occupy within every single breakpoint. If nothing is set the value will be inherited from smaller (left) to bigger (right) viewport. The Main area will fill the space between these sidebars.


**Step 3: Use in Contents**

CSS-Classes and their styles will now be applied automatically, when you define bootstrap layout properties in your contents (forms, modules, articles & content elements)


### Widget <a name="widget"></a>
If you need a custom property to be responsively set, you can use the ResponsiveWidget (inputtype responsive) or the OptionalResponsiveWidget (inputtype optionalResponsive). Therefore define a 'responsiveInputType' which needs to contain the widget-alias you want to multiply for all given breakpoihts. 
To store the values in your DB use a blob field.