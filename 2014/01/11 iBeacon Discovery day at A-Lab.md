# iBeacon Discovery day at A-Lab
Held at 2014-01-11 at Appsterdam HQ with 40 Appsterdammers.
        
|Key|Value
|---|---|
|id|[158634292](https://www.meetup.com/appsterdam/events/158634292/)|
|name|iBeacon Discovery day at A-Lab|
|organizer|Appsterdam|
|attendees|40|
|begin date|2014-01-11|
|end date|2014-01-11|
|price|0|
|location name|Appsterdam HQ|
|location address|A Lab, Overhoeksplein 2, 1031 KS, Amsterdam, Amsterdam|
|latitude|52.38422|
|longitude|4.902793|
|(in-app) icon|🔬|

---

As Appsterdammer you are invited to try the first Glimworm iBeacons at A-Lab.

UPDATE: Material for the day is available at the following web address [http://www.glimworm.com/ibeacon-discovery-day-workshop/](http://www.glimworm.com/ibeacon-discovery-day-workshop/)

**What is an iBeacon?**

iBeacon is technology that allows mobile apps to recognize when an iPhone is near a small wireless sensor called a beacon. The beacon can transmit data to an iPhone - and visa versa - using Bluetooth Low Energy (BLE).

iBeacon is a feature in iOS 7, thus Apple's new iPhones will have iBeacon. Say you own an iPhone 5S and you're walking by a Starbucks that has a beacon. When you enter its zone, the beacon will transmit special promotions, coupons, recommendations, etc, to your iPhone via the Starbucks app. Beacons will also accept payments, so you can pay for a Starbucks coffee without having to bump or tap your phone against anything.

This hands on iBeacon day is offered and hosted by our sponsor [Glimworm](http://www.glimworm.com/), who hosts our [Wednesday Lectures](http://www.meetup.com/Appsterdam/events/155205422/) every week. 

**

Hands on**

There will be a minimum of 5 prototype iBeacons and the ability to set them up in any configuration. The intention of the day get as many developers, UX and UI designers to come together and explore creative possibilities.

If you are an IOS developer you probably have seen the new additions to Core Bluetooth that allow interaction with iBeacons that were added with IOS7. You may have tried it out by turning an iPhone or a Mavericks laptop into a beacon. This day you will have the opportunity to write some code that works in a room with multiple programmable beacons because that is what it is all about.

Glimworm wrote a simple configuration app to detect and program the beacons remotely to set them up with any UUID, major and minor numbers you want to try out. They are battery operated so we can move them around into all sorts of funky places.

Since there is a lot of press about iBeacons and you will find three articles with more information at the bottom of this page. The first shows us 10 things we can already do with iBeacons using the [Geohopper App](https://itunes.apple.com/us/app/geohopper/id605160102), the second gives 5 examples where UX design will change, and the third gives 4 ways of how iBeacons will disrupt Interaction design.

We would like to try as many of these concepts as possible so the more coders, UX and UI designers who come along the better.

The day will be filmed so that we can make a video for the website and maybe even for Kickstarter or Indigogo.

**Explanation for developers wanting to join**

If you have not worked with iBeacons before think of it like a hotspot. You program into your app the UUIDs of the beacons you want to respond to and then when you come in range you get a system notification which can wake your app and you get a payload of a major code a minor code and a proximity. When you have 3 or more you can use the proximity to also triangulate a local position.

At their simplest they act like QR codes or NFC without you needing you to point you phone at them or touch them at 30m distance.  At their most impressive they could be used for micro-location.

Many beacons can share a UUID so you can listen for a all beacons with the same UUID and then find all the beacons around you, then you can use the major and minor numbers to indicate which area you are passing through and which beacon you are near.

Alternatively you can listen only for one beacon by listening only for a single combination of UUID, Major and Minor.

**API**

Glimworm created a simple cloud management platform for the beacons which you can use for registering the beacons and connection beacons to advertised content in your apps. This can be used in the cloud or downloaded as open source.  An instance will be up and running on a server for this day.

**Any Passbook experts out there?**

If anyone knows how to program for passbook you can link them to passbook entries and we would like to to see this working, so please come and help us!

**Who knows how to Triangulate?**

Also if you know about location triangulation we’d love to see someone try this.

**Bring a pie (Raspberry of course)**

You can set up the Raspberry Pi as iBeacon and Glimworm will bring a couple along to use a Raspberry PI working as a beacon. You are welcome to being your own Pi and and a blank SD card and we will burn the beacon Pi beacon software for you.

You need to bring a BT4 USB unit with you, you can get one [here](http://www.mycom.nl/desktops-randapparatuur/netwerk-internet/bluetooth-dongle/506858/sitecom-micro-bluetooth-40) if you don’t have one.

**

I want my own iBeacon**

Besides the iBeacon prototypes to play with, Glimworm can also make up your very own iBeacon without a fancy case and which would look perfect on the set of Mad Max...  

It consists of an HM-10 bluetooth transmitter which can be wired up to your USB port using an FTDI adapter: 

<img src="http://photos3.meetupstatic.com/photos/event/7/1/9/a/600_320969082.jpeg" />

If you would like to have one of those, you can order one when RSVPing (question is posed). Bring €20 in cash (HM-10 is €5, FTDI adapter is €15). Or you can order an [FTDI adapter](http://lowpowerlab.com/shop/FTDI-Adapter) and an [HM-10](http://imall.iteadstudio.com/im130614001.html) yourself.

Bring your laptop or USB charger, as well as a USB-MiniB cable:

<img src="http://photos3.meetupstatic.com/photos/event/8/f/1/8/600_321276632.jpeg" />

**

Further reading**

For some more background, here are the three articles we will use

***Article 1 : 10 Things you can do now with iBeacons***

Geohopper is a suite of ready made apps for automating beacons. In their blog they cite [10 things you can do now with iBeacons](http://blog.twocanoes.com/post/68861362715/10-awesome-things-you-can-do-today-with-ibeacons).

 1. “Auto-Lock and walk” your Mac

 2. Flip iChat Status to “Out” when stepping away

 3. Send a notification as you arrive

 4. Create your own “club card” with passbook

 5. Remotely snap a webcam photo and email it to yourself

 6. Light turn on when you arrive

 7. Post to group chat  as staff enter or leave the building

 8. Make a dramatic entrance as “Eye of the Tiger” starts playing when you walk in.

 9. Keep a log of when you enter and leave your car in google docs

10. Post a message to Twitter when near a beacon

We want to try all of these on the 11th so please volunteer if you want to make on of them happen.

***Atricle 2 : 5 UX Predictions for a World of iBeacons in 2014***

Doug Thompson writes in his article about [how beacons will inspire a new generation of UX designers](http://beekn.net/2013/12/5-ux-predictions-for-ibeacons-2014/)

1. iBeacons as Pins, Drop-Offs and Bulletin Boards

2. Experiences Where The User is the Beacon

3. Beacons Will Tie to Physical World UX

4. Most Beacons Will Be Invisible to the User

5. Some Beacons Will Glow

Doug Thompson is a longtime blogger on technology and a serial entrepreneur. He is based in Toronto, Canada and is Co-Founder of LOCOLO, Ltd. which is helping brands and organizations to reach consumers through iBeacon and the Internet of Things. You can drop him a line at [[masked]](mailto:[masked]) or follow him on [Twitter](https://twitter.com/Dusanwriter) or [LinkedIn](http://www.linkedin.com/profile/view?id=18258706&authType=NAME_SEARCH&authToken=R9ZN).

***Article 3 : 4 reasons why Apple's iBeacon is about to disrupt interaction design***

Kyle Vanhemert of Wired writes in his article how the world of interaction design is changing due to what he calls the [most exciting technological trend of 2013](http://www.wired.co.uk/news/archive/2013-12/12/apple-ibeacon).

1. Tying digital content to the physical world

2. Seamless setup for all your gadgets

3. Retail 2.0

4. A new level of peer-to-peer smarts

**Acknowledgment** 

Glimworm's Paul Mainwaring and Jonathan Carter decided only a month ago to start producing iBeacons. They formed a consortium with hardware genius Sven Haitjema and industrial designers John Tillema and Dimer Schaefer, together called [Tweetonig](http://www.tweetonig.nl/). They are now ready to mass produce iBeacons which will be called the “GlimWorm”.

Paul, Jonathan, Sven, John and Dimar will all be present to conduct the event.

At this day, Glimworm will be taking pre-orders for packs of four, for 100 euro per pack.

<img src="http://photos1.meetupstatic.com/photos/event/9/e/0/4/600_320980452.jpeg" />


