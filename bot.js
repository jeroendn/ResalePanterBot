require('dotenv').config();

const { Client, Events, GatewayIntentBits } = require('discord.js');

const bot = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent,
    ]
});

bot.on(Events.ClientReady, () => {
    console.log("READY!");
});

bot.on(Events.MessageCreate, msg => { // Message function
    console.log(msg)
    if (msg.author.bot) return; // Ignore all bots

    if (msg.content === 'ping') { // When a player does '!ping'
        msg.reply('pong') // The bot will say @Author, Pong!
    }
});

bot.login(process.env.DISCORD_TOKEN);