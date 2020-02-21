import React, { Component } from 'react';
import ReactDOM from 'react-dom';
export default class Purchase extends Component {
	constructor(props) {
	    super(props);
	    // this.state is special variable
	    // this.setState is function to switch this variable'
	    // modify state will trigger component updates
	    this.state = {
	      error: null,
	      isLoaded: false,
	      items: [],
	    };
	  }
	
	componentDidMount() {
	    fetch("/api/iap/items?access_token=" + API_TOKEN, {
	    	method: 'GET',
	    	headers: {
	    		'X-Requested-With': 'XMLHttpRequest',
	    	}
	    })
      .then(res => res.json())
      .then(
        (result) => {
          this.setState({
        	  error: null,
            isLoaded: true,
            items: result.items,
          });
        },
        (error) => {
          this.setState({
        	  error: error,
              isLoaded: true,
          });
        }
      )
	}
	
	renderLoading () {
		return <div id="my-purchase"><img src="/img/loading.gif"/></div>;
	}
	
	buy(e) {
		const svname = document.getElementById('my-purchase').getAttribute('data-svname');
		const role = document.getElementById('my-purchase').getAttribute('data-role');
		e.preventDefault();
		
		  var data=new FormData(e.target);
		  data.append('svname', svname);
		  data.append('role', role)
		  const uri="/api/order?access_token=" + API_TOKEN;
		  axios.post(uri, data)
	      .then(
	        (result) => {
	        	console.log(result);
	        	if (typeof(Android) == 'undefined')
	        	{
	        		// purchase-mod handle
	        		console.log("Goto web payment");
	        		window.open('/iap/purchase-item?item='+result.data.item+'&order='+result.data.order);
	        	}
	        	else
	        	{
	        		// native device handle
	        		console.log("Goto google payment");
	        		Android.buy(0, result.data.item, result.data.order);
	        	}
	        })
	        .catch((error) => {
	        	alert("Buy item error! Please contact our supporter! " + error);
	        });
	}
	
	renderItems() {
		const items = this.state.items;
		var list = [];
		items.map((item) => {
			list.push(<div key={item.merchant_id} className="my-purchase-item">
				<form onSubmit={(e) => this.buy(e)}>
					<input type="hidden" id="item" name="item" value={item.merchant_id} />
					<p className="merchant-title">{item.merchant_title}</p>
					<img className="merchant-image" src={item.merchant_image} alt={item.merchant_image} />
					<p className="merchantPrice">Price: {item.price} USD</p>
					<button type="submit">Buy</button>
				</form>
			</div>);
		});
		if (list.length > 0) {
			return (<div className="my-purchase">
				{list}
			</div>);
		}
		else {
			return <div className="my-purchase"><p>No merchant is available now! Plz try again later!</p></div>;
		}
	}
	
	render() {
		const isLoaded = this.state.isLoaded;
		if (isLoaded)
			return this.renderItems();
		else
			return this.renderLoading();
	}
}

if (document.getElementById('my-purchase')) {
    ReactDOM.render(<Purchase />, document.getElementById('my-purchase'));
}
