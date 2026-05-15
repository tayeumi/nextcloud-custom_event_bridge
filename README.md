"# nextcloud-custom_event_bridge"
ứng dụng bắt sự kiện chat để làm realtime ứng dụng.
Nextcloud app → publish Redis event -- > websocket

\*cách test monitor:
Subscribe Redis: trên redis

```bash
  redis-cli SUBSCRIBE chat.events
```

Mở terminal khác:

```bash
  redis-cli PUBLISH chat.events '{"event":"test"}'
```
