{{ header }}

<h2>Your request has been completed</h2>

<p>Hi {{ customer_name }},</p>
<p>Your request <strong>{{ order_id }}</strong> has been completed and you can see it by clicking to link below of by logging in to your account</p>

<a href="{{ site_url }}/customer/orders/view/{{ order_id }}" class="button button-blue">View request here</a> or <a href="{{ site_url }}">Explore more</a>

<br />

<p>If you have any questions, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
