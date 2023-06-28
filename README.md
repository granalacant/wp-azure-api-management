# WP Azure API Management

A client for Azure API Management to manage APIs and expose them to WordPress.

## How to use?

> **_Note:_** The plugin is not published on Wordpress marketplace, so you need to install it manually by downloading it from Github repository.

-   Download the plugin by [clicking here](https://github.com/granalacant/wp-azure-api-management/archive/refs/heads/main.zip).
-   Unzip it on the Wordpress plugin folder, usually: `/wp-content/plugins`.
-   Activate the plugin on Plugin menu from Wordpress admin page.
-   Go to the new Azure API Management menu option.
-   Select "Add new"
-   Give it a name and upload the JSON or YAML contract file, see [How to get Azure API Management contract file](#how-to-get-azure-api-management-contract-file).
-   Copy the shortcode like [WPAPIM id=xxx] displayed on right or in the "All APIs" list.
-   Please remember to properly [setup CORS on Azure API Management instance](#how-to-setup-cors-on-azure-api-management).

### How to get Azure API Management contract file

Export the desired API from the Azure API Management instance by:

-   Go to APIs menu
-   Click on API context menu
-   Select Export option
-   Choose YAML or JSON format

### How to setup CORS on Azure API Management

Since Wordpress instance is outside Azure API Management infrastructure, it will be required to setup CORS from Azure API Management:

-   Go to APIs menu
-   Click on API name
-   Click on All operations
-   Add a new policy on Inbound processing section adding the following:

```
        <cors allow-credentials="true">
            <allowed-origins>
                <origin>$ORIGIN_URL</origin>
            </allowed-origins>
            <allowed-methods>
                <method>*</method>
            </allowed-methods>
        </cors>
```

-   Replace `$ORIGIN_URL` by Wordpress base url. e.g: `http://localhost:80/`.
-   Click on Save button.

## Frequently Asked Questions

### Do I need a Swagger hub account for this?

No. You don't require a Swagger hub account for this.

### I have a file on a remote server, can I use that?

Yes, you can use the shortcode like this: [WPAPIM url="https://path/to/file.json"] or [WPAPIM url="https://path/to/file.yaml"]
