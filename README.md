# Notification System:

## Walking Skeleton

Here's a walking skeleton for notification system.

### Create Notification

We serve as a great way to decouple various aspect of notification and channels since each one of them is a detached class which provides a low coupling for reliable, scalable, and maintainable system.

You can manually creating new Notifcation or via command that will generat boilerplate.

For example `aqarmap make:notification NewListing` it will generate NewListing notification with boilerplate inside `Aqarmap\NotificationBundle\Notifications`.

After creating notification class you can define configration via configration file.

`notifications.yml`

    NewListing:
        class: Aqarmap\NotificationBundle\Notifications\NewListing
        channel: ['sms', 'database', 'mail']
        queue: ['sms', 'database', 'mail']

Or inside class.

`NewListing.php`

    /**
     * List of channels
     *
     * @var array
     */
    public $channel = ['database'];

    /**
     * List of queues
     *
     * @var array
     */
    public $queue = [];


We also provides a simple queue implementation, allowing you to asynchronous sending for particular channel by define queue for example

`notifications.yml`

    NewListing:
        class: Aqarmap\NotificationBundle\Notifications\NewListing
        channel: ['sms', 'database', 'mail']
        queue: ['mail']

Or inside class `NewListing.php`

    /**
     * List of queues
     *
     * @var array
     */
    public $queue = ['mail'];

by default notification will send `synchronize` here's we defiend `mail` to be `asynchronous`

### Create Channel

Since each channel is a detached we provides out of the box `Mail` and `DataBase`channels you can add more channels such as telegram, facebook, twitter and so on.
all channels is stored in `Aqarmap\NotificationBundle\Channels` namespace.

### Dispatch Notification

To dispatch a notification simple by running

    $manager = new ChannelManager();

    $manager->send($users, $notification);

Where `ChannelManager` is a factory or builder that build appropriate driver for each channel if driver not found it will throw `DriverNotFoundException` where sending Notification It is done through `NotifictionSender`

That's a quick intro for notification system. Happy coding!

download repo [here](https://github.com/OmarMakled/notification)
